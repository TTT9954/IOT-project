#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>// 6.10.0
#include <SoftwareSerial.h> // 2.5.0
#include <LiquidCrystal.h>
#include <Adafruit_Fingerprint.h>
#include <Servo.h>

#include "FP.h"
//const byte RX = D5;
//const byte TX = D6;
//SoftwareSerial mySerial = SoftwareSerial(RX, TX);

// khai báo mảng kết nối wifi
const char* ssid = "123456789";
const char* pass = "thanhtu54";
const char* mqtt_server = "ngoinhaiot.com"; //
int mqtt_port = 1111;
const char* mqtt_user = "thanhtu54";
const char* mqtt_pass = "BD6085B020774167";
String topicsub = "thanhtu54/fingerprint_from_Web"; 
String topicpub = "thanhtu54/fingerprint_from_ESP"; 

const char* linkwrite = "http://10.79.232.250:80/MYSQLESP1/fingerprint_verification.php";
const char* linkwrite1 = "http://192.168.137.1:80/MYSQLESP1/fingerprint_register.php";
const char* linkwrite2 = "http://192.168.137.1:80/MYSQLESP1/delete_fingerprint.php";
const char* linkwrite3 = "http://192.168.137.1:80/MYSQLESP1/Empty_fingerprint.php";
const char* link = "http://192.168.137.1:80/MYSQLESP1/getdata.php";

//const char* linkwrite = "http://thanhtu54.000webhostapp.com/fingerprint_verification.php";
//const char* linkwrite1 = "http://thanhtu54.000webhostapp.com/fingerprint_register.php";
//const char* linkwrite2 = "http://thanhtu54.000webhostapp.com/delete_fingerprint.php";
//const char* linkwrite3 = "http://thanhtu54.000webhostapp.com/Empty_fingerprint.php";
//const char* link = "http://thanhtu54.000webhostapp.com/getdata.php";

WiFiClient espClient;
PubSubClient client(espClient);
Servo myservo; 

unsigned long previousMillis = 0;
unsigned long interval = 10000;

String DataMqttJson = "";

String DataSend = "";

String postData ;

long last1 = 0;
String Data = "";
long last = 0;



void SendLenh();
void ConnectWiFi();
void ConnectMqtt();
void callback(char* topic, byte* payload, unsigned int length);
void reconnect_MQTT();
void ReconnectWifi();
void InSertMySql_Fingerprint_Verification(String(ID_verification), String(Name_verification));
void InSertMySql_ID_enroll(String Data_ID, String Name);
void InSertMySql_ID_delete(String Data);
void InSertMySql_Empty();
void GetFingerprintID(String finger);
uint8_t RegistryNewFinger(uint16_t LocationID);
void Registry();
void enroll();
void Find_Finger();
void Empty_FP();
void flag_FP();


uint8_t id;
int ID_FP = -1;
int Save_FP = 0;
int enroll_FP = 0;
int delete_FP = 0;
int empty_FP = 0;
String name_FP= "";
int En = 0;
int ID_enroll_web = 0;
int ID_delete_db = 0;
int flag = 0;
long Timeout_FP = 0;
String ID;
String Name;
String ID_lcd = "";
String Name_lcd = "";
String id_verification = "";

int flag_servo = 0;
int flag_servo1 = 0;
int pos = 0;
int degree;

//Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

const int rs = 52, en = 50, d4 = 53, d5 = 51, d6 = 49, d7 = 47;
LiquidCrystal lcd(rs, en, d4, d5, d6, d7);

void setup()
{
  Serial.begin(9600);
  Init();
  ConnectWiFi();
  ConnectMqtt();
  lcd.begin(16, 4);
  delay(500);
}

void loop()
{  
  DuytriMQTT();
  Find_Finger();
  flag_FP();
  SendDataMQTT();    
}

void callback(char* topic, byte* payload, unsigned int length)
{
  Serial.print("Message topic: ");
  Serial.println(topic);
  for (int i = 0; i < length; i++)
  {
    Data += (char)payload[i];
  }
  Serial.print("Data nhận MQTT: ");
  Serial.println(Data);

  ParseJson(Data);
  
  Data = "";  
}

void ParseJson(String Data)
{
  // đưa dữ liệu json vào thư viện json để kiểm tra đúng hay sai , đúng thì tách dữ liệu => điều khiển
  const size_t capacity = JSON_OBJECT_SIZE(2) + 256;
  DynamicJsonDocument doc(1024);
  DeserializationError error = deserializeJson(doc, Data);
  if (error)
  {
    Serial.println("Data JSON Error!!!");
    return;
  }
  else
  {
    Serial.println();
    Serial.println("Data JSON from MQTT: ");
    serializeJsonPretty(doc, Serial);

      if (doc["Enroll"] == "1")
      {       
        Serial.println("Enrolling !!!");  
        String Data_Enroll_id = doc["enroll_ID"];   
        String name_enroll_db = doc["enroll_name"];  
        id =   Data_Enroll_id.toInt();  
        name_FP = name_enroll_db;
        if(id == 0)
        {
          return;
        }
        else
        {
          enroll_FP = 1;
          lcd.setCursor(0, 0);
          lcd.print("Enroll #ID ");
          lcd.print(id);
          lcd.setCursor(0, 1);
          lcd.print("with Name: ");
          lcd.print(name_FP);
          delay(1000);
          enroll();      
        }
      }
      else if (doc["Delete"] == "1")
      {
        delete_FP = 1;
        String Data_Delete = doc["delete_ID"]; 
        Serial.println("Deleting !!!");
        id =  Data_Delete.toInt(); 
        Delete_FP();      
      }    
      else if (doc["Empty"] == "1")
      {
        empty_FP = 1;
        Empty_FP();
      }
  }
}

void Datajson()
{
  char buffer[256];
  Save_FP = GetNumberOfFinger();

  DynamicJsonDocument doc(1024);  
  doc["saved_fingerprint"] = String(Save_FP);
  doc["ID_enroll"] = String(ID_enroll_web);
  doc["Enroll"] =  String(En);
  doc["ID"] = String(ID_FP);
  doc["Name"] = String(Name);
  serializeJson(doc, buffer);
  Serial.print("DataMqttJson: ");
  Serial.println(buffer);
  client.publish(topicpub.c_str(), buffer); 
  
}

//  DataMqttJson  = "{\"saved_fingerprint\":\"" + String(Save_FP) + "\"," +
//                    "\"ID_enroll\":\"" + String(ID_enroll_web) + "\"," +
//                    "\"Enroll\":\"" + String(En) + "\"," + 
//                    "\"ID\":\"" + String(ID_FP) + "\"," +
//                    "\"Name\":\"" + String(Name) + "\"}"; // JSON üzenet
// közzéteszi az üzenet a témával = "thanhtu54/fingerprint_from_ESP"; 
// client.publish(topicpub.c_str(), buffer); 

void Publish_message()
{
  char buffer[256];

  DynamicJsonDocument doc(1024); 
   
  doc["kulcs_1"] = "érték_1";
  doc["kulcs_2"] = "érték_2";
  doc["kulcs_3"] = "érték_3";
  serializeJson(doc, buffer); 
  
  client.publish("user1/test", buffer); 
}

// buffer = {"kulcs_1":"érték_1","kulcs_2":"érték_2","kulcs_3":"érték_3","kulcs_4":"érték_4"}
void SendDataMQTT()
{
   if (millis() - last >= 1000)
  {
    if (client.connected())
    {
      Datajson();     
    }
    last = millis();
    Name = "";
//    ID_FP = -1;
  }    
}

void InSertMySql_ID_enroll(String Data_ID, String Name)
{
   HTTPClient http;
   http.begin(linkwrite1);
   http.addHeader("Content-Type", "application/x-www-form-urlencoded");
   String httpRequestData = "ID_enroll="   + String(Data_ID)
                           + "&Name=" + String(Name) + "";                   
   // Send HTTP POST request về databasephp
   int httpResponseCode = http.POST(httpRequestData);
   // nếu đưa dữ liệu thành công httpResponseCode == 200
   // nếu không đưa dữ liệu dc httpResponseCode == -1
   if (httpResponseCode == 200)
   {       
        Serial.print("Registered fingerprint successfully:");
        Serial.println(httpRequestData);      
   }
   else
   {
        Serial.println("Không gửi dữ liệu được!!!");
   }
   http.end();         
}


void InSertMySql_ID_delete(String Data)
{      
    HTTPClient http;
    http.begin(linkwrite2);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String httpRequestData = "ID_delete=" + String(Data) + "";                     
    // Send HTTP POST request về databasephp
    int httpResponseCode = http.POST(httpRequestData);
    // nếu đưa dữ liệu thành công httpResponseCode == 200
    // nếu không đưa dữ liệu dc httpResponseCode == -1
    if (httpResponseCode == 200)
    {       
         Serial.print("Deleted successfully:");
         Serial.println(httpRequestData);      
     }
     else
     {
          Serial.println("Không gửi dữ liệu được!!!");
     }
     http.end();               
}

void InSertMySql_Empty()
{      
    HTTPClient http;
    http.begin(linkwrite3);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String httpRequestData = "ID_empty=" + String(1) + "";                     
    // Send HTTP POST request về databasephp
    int httpResponseCode = http.POST(httpRequestData);
    // nếu đưa dữ liệu thành công httpResponseCode == 200
    // nếu không đưa dữ liệu dc httpResponseCode == -1
    if (httpResponseCode == 200)
    {       
         Serial.print("Emptied successfully: ");
         Serial.println(httpRequestData);      
     }
     else
     {
          Serial.println("Không gửi dữ liệu được!!!");
     }
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    http.end();                 
}

void GetFingerprintID(String fingerID){
  
  HTTPClient http;    //Declare object of class HTTPClient
  //Post Data
  postData = "FingerID=" + String(fingerID); // Add the Fingerprint ID to the Post array in order to send it
  // Post methode

  http.begin(link); //initiate HTTP request, put your Website URL or Your Computer IP 
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
  
  int httpResponseCode = http.POST(postData);   //Send the request
  
  if (httpResponseCode == 200)
  {       
     Serial.print("Send data successfully: ");
     Serial.println(postData);  
     String payload = http.getString();    //Get the response payload
     ID = payload.substring(0, payload.indexOf("/"));
     Name = payload.substring(payload.indexOf("/")+1 , payload.length());
     Serial.println("ID: " + ID);
     Serial.println("Name: " + Name);    
  }
  else
  {
     Serial.println("Không gửi dữ liệu được!!!");
  }
  postData = "";
  http.end();  //Close connection
}


void InSertMySql_Fingerprint_Verification(String(ID_verification), String(Name_verification))
{
  HTTPClient http;
  http.begin(linkwrite);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String httpRequestData = "ID_FP="  + String(ID_verification)
                           + "&Name="  + String(Name_verification) + "";
  // Send HTTP POST request về databasephp
  int httpResponseCode = http.POST(httpRequestData);
  // nếu đưa dữ liệu thành công httpResponseCode == 200
  // nếu không đưa dữ liệu dc httpResponseCode == -1
  if (httpResponseCode == 200)
  {

    Serial.print("Send Data Thành Công:");
    Serial.println(httpRequestData);

  }
  else
  {
    Serial.println("Không gửi dữ liệu được!!!");
  }
  http.end();
}

void DuytriMQTT()
{
  if (!client.connected())
  {
      reconnect_MQTT();     
  }
  client.loop();
}
// char a[]


// hàm kiểm tra và kết nối MQTT
void reconnect_MQTT()
{

  while (!client.connected())
  {
    String clientId = String(random(0xffff), HEX); // các id client esp không trung nhau => không bị reset server
    if (client.connect(clientId.c_str(), mqtt_user, mqtt_pass))
    {
      Serial.println("Connected MQTT mqtt.ngoinhaiot.com!!!");

      client.subscribe(topicsub.c_str());
    }
    else
    {
      Serial.println("Disconnected MQTT mqtt.ngoinhaiot.com!!!");
      delay(5000);
    }
  }
}


void ConnectMqtt()
{
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback); 
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

uint8_t RegistryNewFinger(uint16_t LocationID)
{
  uint8_t Result=FP_NOFINGER;
  SendDataMQTT();
  //lcd_gotoxy_string(0,0,"Put finger in pl");
  // A "while" ciklus a függvényben addig fut, amíg a modul sikeresen be nem olvas egy ujjlenyomatot.
  Serial.println("Put your finger on sensor to register");
  long timeout = millis();
  while(Result==FP_NOFINGER && millis() - timeout < 5000) 
  {
    SendDataMQTT();
    SendFPHeader();
    SendFPGetImage();
    Result=CheckFPRespsone(12);
    Serial.print(".");
  }
  if(Result != FP_OK)
  {
    return FP_NOFINGER;
  }
  Serial.println();
  // Ez a rész a képpufferben lév? képet karakterfájllá alakítja, amely a modul Buffer1-ben van tárolva.
  SendFPHeader();
  SendFPCreateCharFile1();
  Result=CheckFPRespsone(12);

  // Ez a rész arra vár, hogy a felhasználó eltávolítsa a beolvasott ujját; addig leáll a "while" ciklusban, amíg nem észlel ujjat.
  Serial.println("Remove finger");
  En = 2;
  SendDataMQTT();
  delay(2000);
  
  Result=FP_NOFINGER;
  Serial.println("Put finger again");
  En = 3;
  SendDataMQTT();
  long timeout1 = millis();
  // Ezután az ujjat ismét beolvassa, és a "while" ciklus csak akkor szakad meg, ha a kép sikeresen létrejött.
  while(Result==FP_NOFINGER && millis() - timeout1 < 5000) 
  {
    SendDataMQTT();
    SendFPHeader();
    SendFPGetImage();
    Result=CheckFPRespsone(12);
    Serial.print(".");
  }
  // Az alábbi szakasz a képet karakterfájllá alakítja, amelyet a Buffer2-ben tárolhat, ahogy korábban is történt.
  if(Result != FP_OK)
  {
    return FP_NOFINGER;
  }
  
  SendFPHeader();
  SendFPCreateCharFile2();
  Result=CheckFPRespsone(12);
  
  // Ezután egy modell/sablon generálódik az 1. és 2. pufferben lév? kombinált adatok felhasználásával. A létrehozott sablont az 1. és 2. puffer tárolja.
  SendFPHeader();
  SendFPCreateTemplate();
  Result=CheckFPRespsone(12);
  if(Result==FP_FINGER_NOTMATCH)
  {
    return FP_FINGER_NOTMATCH;
  }
  
  // Végül a sablon eltárolásra kerül. Ha sikeres, a modul eltárolja a sablont a céloldalazonosítón, és kilép a függvényb?l.
  SendFPHeader();
  SendStoreFinger(LocationID);
  Result=CheckFPRespsone(12);
  if(Result!=FP_OK) return FP_ERROR;
  else
  {
    return FP_OK;
  }

}

void Registry()
{
  uint8_t FingerResult;
  FingerResult=RegistryNewFinger(id);
  if(FingerResult==FP_OK)
  {
    flag = 1;
    Serial.print("Stored ID #");
    Serial.println(id);
    Serial.println("Registry Done!");
    En = 5;
    SendDataMQTT();
  }
  else if(FingerResult==FP_FINGER_NOTMATCH)
  {
    Serial.println("Not match!");
    En = 4;
    SendDataMQTT();
  }
  //
  delay(2000);
}

void enroll()
{
  while ( enroll_FP == 1)
  {     
      ID_FP = -2;
      En = 1;
      SendDataMQTT();
      Registry();
      En = 0;
      ID_FP = -1; 
      ID_enroll_web = 0;
      enroll_FP = 0; 
      ID_lcd = Name_lcd = "";     
  }
}

void Find_Finger()
{
  if(millis() - Timeout_FP >= 1000)
  {
    uint8_t FingerResult;
    FingerResult= CheckFinger();
    if(FingerResult==FP_OK)
    {
      flag = 4;
      ID_FP = IDFromFinger;
      Serial.print("Found with ID #");
      Serial.println(ID_FP);    
    }
    else if(FingerResult==FP_FINGER_NOTFOUND)
    { 
      flag = 5;
      //flag_servo = 0;
      ID_FP = 0;
      Serial.println("Finger Not Found");
    }
    else
    {
      ID_FP = -1;
      //flag_servo = 0;
      Serial.println("No finger detected!");
    }  
    Timeout_FP = millis();
  }
}

void Delete_FP()
{
  if(delete_FP == 1)
  {
    flag = 2;
    Delete_ID(id);
    delete_FP = 0;
  }  
}

void Empty_FP()
{
  if(empty_FP == 1 && GetNumberOfFinger() != 0)
  {
      flag = 3;
      Empty_Database();  
      Serial.println("Now database fingerprint is empty :)"); 
      empty_FP = 0;      
  }
  else if (empty_FP == 1 && GetNumberOfFinger() == 0)
  {
    Serial.println("Sensor doesn't contain any fingerprint data.");
    empty_FP = 0;
  }
}

void flag_FP()
{
  if(flag == 1)
  {
    InSertMySql_ID_enroll(String(id), String(name_FP));
    flag = 0;
  }
  if(flag == 2)
  {
    InSertMySql_ID_delete(String(id));
    flag = 0;
  }
  if(flag == 3)
  {
    InSertMySql_Empty();
    flag = 0;
  }
  if(flag == 4)
  {
    GetFingerprintID(String(ID_FP));
    InSertMySql_Fingerprint_Verification(String(ID_FP), String(Name));
    flag = 0;
  }
  if(flag == 5)
  {
     String id_state="Not valid";
     InSertMySql_Fingerprint_Verification(id_state, "");
     flag = 0;
  }
}
