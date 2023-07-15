#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>// 6.10.0
#include <SoftwareSerial.h> // 2.5.0
#include "DHT.h"
#include "DHT11.h"

#define DEN D1
#define NUTDEN D2
#define DHTPIN D3 
#define DHTTYPE DHT11 
WiFiUDP u;
NTPClient timeClient(u, "europe.pool.ntp.org", 2*3600);

// khai báo mảng kết nối wifi
const char* ssid = "123456789";
const char* pass = "thanhtu54";

//const char* ssid = "iPhone";
//const char* pass = "thanhtu54";

// tạo ra biến để lưu thông số server


const char* mqtt_server = "ngoinhaiot.com"; 
int mqtt_port = 1111;
const char* mqtt_user = "thanhtu54";
const char* mqtt_pass = "BD6085B020774167";

String topicsub = "thanhtu54/livingroom_from_Web"; // nhận dữ liệu ESP ở topic toannv10291/quat => APP WEB gửi toannv10291/quat
String topicpub = "thanhtu54/livingroom_from_ESP"; // gửi dữ liệu

const char* linkwrite = "http://192.168.137.1:80/MYSQLESP1/ESPwrite_livingroom.php";
//const char* linkwrite1 = "http://192.168.137.1:80/MYSQLESP1/ESPwrite_email.php";

WiFiClient espClient;
PubSubClient client(espClient);
DHT dht(DHTPIN, DHTTYPE);

unsigned long previousMillis = 0;
unsigned long interval = 10000;

String DataMqttJson = "";
String Time = "";
String DataSend = "";

int bienTB1 = 0;
int bienTB2 = 0;

long last1 = 0;

long gioduoiden = 0;
long phutduoiden = 0;
long giotrenden = 0;
long phuttrenden = 0;

long giothuc = 0;
long phutthuc = 0;
int biengui = 0;

int HenGioDen = 0;
int TB1 = 0;
float nhietdo = 0;
float doam = 0;
int chon_mode_den = 0;
String Data = "";
long last = 0;

float sensorValue; //Define variable for analog readings 
float _adc, _a, _b, _sensor_volt;
float  _R0, RS_air, ratio, _PPM, _RS_Calc; 
float calcR0 = 0;


void SendLenh();
void Read_UART_JSON();
void ConnectWiFi();
void ConnectMqtt();
void callback(char* topic, byte* payload, unsigned int length);
void reconnect_MQTT();
void ReconnectWifi();



void setup()
{
  Serial.begin(9600);
  Serial.println(F("DHTxx test!"));
  pinMode(DEN, OUTPUT);
  pinMode(A0, INPUT); 
  digitalWrite(DEN, LOW);
  
  pinMode(NUTDEN, INPUT_PULLUP);
  ConnectWiFi();
  ConnectMqtt();
  _a = -0.306;
  _b = 0.653;
  for(int i = 1; i<=10; i ++)
  {
    update(); // Update data, the arduino will read the voltage from the analog pin
    calcR0 += calibrate(3.6);
  }
  setR0(calcR0/10);
  timeClient.begin();
  dht.begin();
//  DHT11Setup();
//  DHT11ReadData();
  
  last1 = millis();
}

void loop()
{
  DuytriMQTT();
  NutNhan();
  ReadTime();
  Mode_onoff_den();
  SendDataMQTT();
}

void  ReadTime()
{

  if (WiFi.status() == WL_CONNECTED)
  {
    if (millis() - last1 >= 1000)
    {
      timeClient.update();
      Time = timeClient.getFormattedTime();
      Serial.print("Time: ");
      Serial.println(Time);
      TachThoiGianThuc(String(Time));
      last1 = millis();
    }
  }


}

void TachThoiGianThuc(String thoigianthuc)
{
  //00:01:56
  String Giothuc, Phutthuc  = "";
  // 19:40  substring( vi tri bắt đầu , vị trí kết thúc)
  Giothuc = thoigianthuc.substring(0, thoigianthuc.indexOf(":"));
  Phutthuc = thoigianthuc.substring(thoigianthuc.indexOf(":") + 1, thoigianthuc.length());
  giothuc = Giothuc.toInt();
  phutthuc = Phutthuc.toInt();

  Serial.print("giothuc: ");
  Serial.println(giothuc);

  Serial.print("phutthuc: ");
  Serial.println(phutthuc);

}

void callback(char* topic, byte* payload, unsigned int length)
{
  Serial.print("Topic: ");
  Serial.println(topic);
  for (int i = 0; i < length; i++)
  {
    Data += (char)payload[i];
  }
  Serial.print("Message from MQTT: ");
  Serial.println(Data);

  ParseJson(Data);

  Data = "";
}

void ParseJson(String payload)
{
  const size_t capacity = JSON_OBJECT_SIZE(2) + 256;
  DynamicJsonDocument JSON1(capacity);
  DeserializationError error1 = deserializeJson(JSON1, payload); 
  // Ellenőrizze a kapott üzenet hibáját
  // például: helyes-e a JSON formátum ? A méret meghaladja a maximális méretet ? stb
  if (error1)
  {
    Serial.println("Data JSON Error!!!");
    return;
  }
  else
  {
    Serial.println();
    Serial.println("Data JSON from MQTT: ");
    serializeJsonPretty(JSON1, Serial);
    Serial.println();
    if (JSON1["Light_livingroom"] == "1")
    {
      if(HenGioDen == 0)
      {
        digitalWrite(DEN, HIGH);
        TB1 = 1;
        Serial.println("Den ON!!!");
      }  
    }
    else if (JSON1["Light_livingroom"] == "0")
    {
      if(HenGioDen == 0)
      {
        digitalWrite(DEN, LOW);
        TB1 = 0;
        Serial.println("Den OFF!!!");   
      }
      
    }
    else if (JSON1["Light_livingroom_timer"] == "1")
    {
        HenGioDen = 1;
        if (JSON1["Mode_on"] == "1")
        {
           chon_mode_den = 1;
           Serial.println("Mode đèn: on");
        }   
       else if (JSON1["Mode_off"] == "1")
        {
          chon_mode_den = 0;
          Serial.println("Mode đèn: off");
        }
        String DataGD = JSON1["GD"];
        String DataPD = JSON1["PD"];
        String DataGT = JSON1["GT"];
        String DataPT = JSON1["PT"];

        gioduoiden = DataGD.toInt();
        phutduoiden = DataPD .toInt();
        giotrenden = DataGT.toInt();
        phuttrenden = DataPT.toInt();


        Serial.print("gioduoiden: ");
        Serial.println(gioduoiden);

        Serial.print("phutduoiden: ");
        Serial.println(phutduoiden);


        Serial.print("giotrenden: ");
        Serial.println(giotrenden);

        Serial.print("phuttrenden: ");
        Serial.println(phuttrenden);
    }
    else if (JSON1["Light_livingroom_timer"] == "0")
    {
       HenGioDen = 0;
    }
  }
}


void Datajson(String DataND, String DataDA, String DataMQ135, String DataTB1, String DataH1)
{
//  DataMqttJson  = "{\"Temp_livingroom\":\"" + String(DataND) + "\"," +
//                  "\"Hum_livingroom\":\"" + String(DataDA) + "\"," +
//                  "\"Light_livingroom\":\"" + String(DataLight_state) + "\"," +
//                  "\"Light_livingroom_timer\":\"" + String(DataH1) + "\"}";
//  Serial.println();
//  Serial.print("DataMqttJson: ");
//  Serial.println(DataMqttJson);
  char buffer[256];

  DynamicJsonDocument doc(1024);  
  
  doc["Temp_livingroom"] = String(DataND);
  doc["Hum_livingroom"] = String(DataDA);
  doc["PPM_livingroom"] = String(DataMQ135);
  doc["Light_livingroom"] = String(DataTB1);
  doc["Light_livingroom_timer"] = String(DataH1);
  serializeJson(doc, buffer);
 // client.publish(topicpub.c_str(), DataMqttJson.c_str());
  Serial.print("DataMqttJson: ");
  Serial.println(buffer);
  client.publish(topicpub.c_str(), buffer);

  // publish(tocpic , data)

}

void SendDataMQTT()
{
  if (millis() - last >= 2000)
  {
    if (client.connected())
    {
      Chuongtrinhcambien();
      Datajson(String(nhietdo), String(doam), String(_PPM+400), String(TB1), String(HenGioDen));
      InSertMySql(String(nhietdo), String(doam), String (_PPM+400), String(TB1), String(HenGioDen));
    }
    last = millis();
  }
}

void InSertMySql(String nhietdo, String doam, String DataPPM, String TB1, String HenGioDen)
{
  HTTPClient http;
  http.begin(linkwrite);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String httpRequestData = "Temp="   + String(nhietdo)
                           + "&Hum=" +  String(doam)
                           + "&PPM=" +  String(DataPPM)
                           + "&Light=" +  String(TB1)
                           + "&Timer=" +  String(HenGioDen)
                           + "";
  int httpResponseCode = http.POST(httpRequestData);
  if (httpResponseCode == 200)
  {
    Serial.print("Sent data successfully:");
    Serial.println(httpRequestData);
  }
  else
  {
    Serial.println("Sent data not successfully !!!");
  }
  http.end();
}

//void InSertMySql()
//{
//  HTTPClient http;
//  http.begin(server_URL);
//  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
//  String httpRequestData = "kulcs_1="   + String(value1)
//                           + "&kulcs_2=" +  String(value2)
//                           + "&kulcs_3=" +  String(value3)
//                           + "";
//  int httpResponseCode = http.POST(httpRequestData);
//  if (httpResponseCode == 200)
//  {
//    Serial.print("Sent data successfully:");
//    Serial.println(httpRequestData);
//  }
//  else
//  {
//    Serial.println("Sent data not successfully !!!");
//  }
//  http.end();
//}

void DuytriMQTT()
{
  if (!client.connected())
  {
    reconnect_MQTT();
  }
  client.loop();
}
// char a[]


void SoSanhHenGio_on_den()
{
  if (HenGioDen == 1)
  {
    if ( gioduoiden == giotrenden)
    {
      if (giothuc == gioduoiden)
      {
        // so sánh theo phút
        if (phutthuc >= phutduoiden && phutthuc < phuttrenden)
        {
          digitalWrite(DEN, HIGH);
          TB1 = 1;
        }
        else if (phutthuc >= phutduoiden && phutthuc >= phuttrenden)
        {
          digitalWrite(DEN, LOW);
          TB1 = 0;
          HenGioDen = 0;
        }
        else
        {
          digitalWrite(DEN, LOW);
          TB1 = 0;
        }
      }
      else
      {
        digitalWrite(DEN, LOW);
        TB1 = 0;
      }
    }
    else if (gioduoiden < giotrenden && giothuc >= gioduoiden && giothuc < giotrenden)
    {
      if(giothuc == gioduoiden && giothuc < giotrenden && phutthuc >= phutduoiden)
      {
        TB1 = 1;
        digitalWrite(DEN, HIGH);
      }
      else if(giothuc == giotrenden &&  giothuc > gioduoiden && phutthuc < phuttrenden)
      {
        TB1 = 1;
        digitalWrite(DEN, HIGH);
      }
      else if(giothuc == giotrenden &&  giothuc > gioduoiden && phutthuc >= phuttrenden)
      {
        HenGioDen = 0;
      }
      else
      {
        TB1 = 0;
        digitalWrite(DEN, LOW);
      }
    }
    else if (giothuc == giotrenden)
    {
      if (phutthuc < phuttrenden)
      {
        TB1 = 1;
        digitalWrite(DEN, HIGH);
      }
      else
      {
        TB1 = 0;
        digitalWrite(DEN, LOW);
        HenGioDen = 0;
      }
    }
    else if ( giothuc > giotrenden || giothuc < gioduoiden)
    {
      TB1 = 0;
      digitalWrite(DEN, LOW);
    }
  }
}

void SoSanhHenGio_off_den()
{
    if (HenGioDen == 1)
    {
      if (gioduoiden == giotrenden)
      {
        if (giothuc == gioduoiden)
        {
          // so sánh theo phút
          if (phutthuc >= phutduoiden && phutthuc < phuttrenden)
          {
            digitalWrite(DEN, LOW);
            TB1 = 0;
          }
          else if (phutthuc >= phutduoiden && phutthuc >= phuttrenden)
          {
            digitalWrite(DEN, HIGH);
            TB1 = 1;
            HenGioDen = 0;
          }
          else
          {
            digitalWrite(DEN, HIGH);
            TB1 = 1;
          }
  
        }
        else
        {
          digitalWrite(DEN, HIGH);
          TB1 = 1;
        }
      }
    else if (gioduoiden < giotrenden && giothuc >= gioduoiden && giothuc < giotrenden)
    {
      if(giothuc == gioduoiden && giothuc < giotrenden && phutthuc >= phutduoiden)
      {
        TB1 = 0;
        digitalWrite(DEN, LOW);
      }
      else if(giothuc == giotrenden &&  giothuc > gioduoiden && phutthuc < phuttrenden)
      {
        TB1 = 0;
        digitalWrite(DEN, LOW);
      }
      else if(giothuc == giotrenden &&  giothuc > gioduoiden && phutthuc >= phuttrenden)
      {
        HenGioDen = 0;
      }
      else
      {
        TB1 = 1;
        digitalWrite(DEN, HIGH);
      }
    }
    else if (giothuc == giotrenden)
    {
      if (phutthuc < phuttrenden)
      {
        TB1 = 0;
        digitalWrite(DEN, LOW);
      }
      else
      {
        TB1 = 1;
        digitalWrite(DEN, HIGH);
        HenGioDen = 0;
      }
    }
    else if ( giothuc > giotrenden || giothuc < gioduoiden)
    {
      TB1 = 1;
      digitalWrite(DEN, HIGH);
    }
  }
}

void Mode_onoff_den()
{
  if(chon_mode_den == 0)
  {
     SoSanhHenGio_off_den(); 
  }
  else if (chon_mode_den == 1)
  {
     SoSanhHenGio_on_den();    
  } 
}

void NutNhan()
{
  if (digitalRead(NUTDEN) == LOW)
  {
    Serial.println("nút nhấn đèn đã được nhấn!!!");
    delay(300);

    while (1)
    {
      DuytriMQTT();
      if (digitalRead(NUTDEN) == HIGH)
      {
        // làm việc
        if (HenGioDen == 0)
        {
          DK_DEN1();
        }

        delay(300);
        // thoát vòng lặp vô hạn
        break;
      }
    }
  }
}

void DK_DEN()
{
  Serial.println("Onclick đèn!!!");
  last = millis();
  if (HenGioDen == 0)
  {
    DK_DEN1();
  }
  last = millis();

}

void DK_DEN1()
{

  if (TB1 == 0)
  {
    digitalWrite(DEN, HIGH);
    TB1 = 1;
    Serial.println("Đèn ON!!!");
  }
  else if (TB1 == 1)
  {
    digitalWrite(DEN, LOW);
    TB1 = 0;
    Serial.println("Đèn OFF!!!");
  }
  // TB1 gửi lên web để hiển thị đèn or tb ON hoặc OFF ( 0 1)


}

void Chuongtrinhcambien()
{
//    DHT11ReadData();
//    doam = I_H + D_H*0.1;
//    nhietdo = I_Temp + D_Temp*0.1;
    update();
    readSensor();
    doam = dht.readHumidity();
    nhietdo = dht.readTemperature();

  if (isnan(doam) || isnan(nhietdo)) {
    Serial.println(F("Failed to read from DHT sensor!"));
    return;
  }

}

void update()
{
  _sensor_volt = getVout();
}

void setR0(float R0) {
  _R0 = R0;
}

float getVout()
{
  float sensor_volt;
  float avg = 0.0;
//  for(int i = 0 ; i< 10 ; i++) 
//  {
//    sensorValue = analogRead(A0); 
//    avg += sensorValue;
//    delay(20);
//  }
  sensor_volt = analogRead(A0) * 5.0 / ((pow(2, 10)) - 1);
  return sensor_volt;
}

float calibrate(float ratioInCleanAir) {
  
  float RS_air; //Define variable for sensor resistance
  float R0; //Define variable for R0
  RS_air = ((5.0*1.0)/_sensor_volt)-1.0; //Calculate RS in fresh air
  if(RS_air < 0)  RS_air = 0; //No negative values accepted.
  R0 = RS_air/ratioInCleanAir; //Calculate R0 
  if(R0 < 0)  R0 = 0; //No negative values accepted.
  return R0;
}

float readSensor()
{
  _RS_Calc = ((5.0*1.0)/_sensor_volt)-1.0; //Get value of RS in a gas
  ratio = _RS_Calc / _R0;   // Get ratio RS_gas/RS_air;
  double ppm_log = (log10(ratio)-_b)/_a; //Get ppm value in linear scale according to the the ratio value  
  _PPM = pow(10, ppm_log); //Convert ppm value to log scale  
  return _PPM;
}
// hàm kiểm tra và kết nối MQTT
void reconnect_MQTT()
{

  while (!client.connected())
  {
    String clientId = String(random(0xffff), HEX); // các id client esp không trung nhau => không bị reset server
    if (client.connect("mqttx_e390b0aa", mqtt_user, mqtt_pass))
    {
      Serial.println("Connected MQTT mqtt.ngoinhaiot.com!!!");

      client.subscribe(topicsub.c_str());
    }
    else
    {
      Serial.println("Disconnected MQTT mqtt.ngoinhaiot.com!!!");
      ReconnectWifi();
      delay(5000);
    }
  }
}



void  ConnectMqtt()
{
  client.setServer(mqtt_server, mqtt_port); // sét esp client kết nối MQTT broker
  delay(10);
  client.setCallback(callback); // => đọc dữ liệu mqtt broker mà esp subscribe
  delay(10);
}

void ConnectWiFi()
{
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  //=============================================================
  Serial.println();
  Serial.println("Connect WiFi");
  Serial.print("Address IP esp: ");
  Serial.println(WiFi.localIP());
}

void ReconnectWifi()
{
  unsigned long currentMillis = millis();
  // if WiFi is down, try reconnecting every CHECK_WIFI_TIME seconds
  if ((WiFi.status() != WL_CONNECTED) && (currentMillis - previousMillis >= interval))
  {
    Serial.print(millis());
    Serial.println("Reconnecting to WiFi...");
    WiFi.disconnect();
    WiFi.begin(ssid, pass);
    Serial.println(WiFi.localIP());
    //Alternatively, you can restart your board
    Serial.println(WiFi.RSSI());
    previousMillis = currentMillis;
  }
}
