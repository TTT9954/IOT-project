#include "Arduino.h"
#include "SoftwareSerial.h" // 2.5.0
#define FP_OK 0x00 // parancs végrehajtása befejezödött
#define FP_ERROR 0xFE 
#define FP_NOFINGER 0x02 // nincs ujj az érzékel?n
#define FP_FINGER_NOTMATCH 0x0A // nem egyesíti a karakterfájlokat
#define FP_FINGER_NOTFOUND 0x09 // nem találja a megfelel? ujjat;

uint8_t FPHeader[6]={0xEF,0x01,0xFF,0xFF,0xFF,0xFF};
uint8_t FPGetImage[6]={0x01,0x00,0x03,0x01,0x00,0x05};
uint8_t FPCreateCharFile1[7]={0x01,0x00,0x04,0x02,0x01,0x00,0x08}; // FPclassCreateCharFile1[4] az 
uint8_t FPCreateCharFile2[7]={0x01,0x00,0x04,0x02,0x02,0x00,0x09};
uint8_t FPCreateTemplate[6]={0x01,0x00,0x03,0x05,0x00,0x09};
uint8_t FPDeleteAllFinger[6]={0x01,0x00,0x03,0x0D,0x00,0x11};
uint8_t FPSearchFinger[11]={0x01,0x00,0x08,0x04,0x01,0x00,0x00,0x00,0xA3,0x00,0xB1}; 
uint8_t FPGetNumberOfFinger[6]={0x01,0x00,0x03,0x1D,0x00,0x21};

const byte RX = D5;
const byte TX = D6;
SoftwareSerial mySerial = SoftwareSerial(RX, TX);

uint8_t IDFromFinger;

uint8_t CurrentNumberFinger;

void Init()
{
  mySerial.begin(57600);
}
void SendFPHeader()
{
  for(int i=0; i<6; i++)
  {
    mySerial.write(FPHeader[i]); 
  }
}

void SendFPGetImage()
{
  for(int i=0; i<6; i++)
  {
    mySerial.write(FPGetImage[i]);
  }
}

void SendFPCreateCharFile1()
{
  for(int i=0; i<7; i++)
  {
    mySerial.write(FPCreateCharFile1[i]);
  }
}

void SendFPCreateCharFile2()
{
  for(int i=0; i<7; i++)
  {
    mySerial.write(FPCreateCharFile2[i]);
  }
}

void SendFPCreateTemplate()
{
  for(int i=0; i<6; i++)
  {
    mySerial.write(FPCreateTemplate[i]);
  }
}

void SendFPDeleteAllFinger()
{
  for(int i=0; i<6; i++)
  {
    mySerial.write(FPDeleteAllFinger[i]);
  }
}

void SendFPDSearchFinger()
{
  for(int i=0; i<11; i++)
  {
    mySerial.write(FPSearchFinger[i]);
  }
}

void SendFGetNumberOfFinger()
{
  for(int i=0; i<6; i++)
  {
    mySerial.write(FPGetNumberOfFinger[i]);
  }
}

void SendStoreFinger(uint16_t IDStore)
{
  uint16_t Sum=0;
  uint8_t DataSend[9]={0};

  DataSend[0]=0x01;
  Sum=Sum+DataSend[0];
  DataSend[1]=0x00;
  Sum=Sum+DataSend[1];
  DataSend[2]=0x06;
  Sum=Sum+DataSend[2];
  DataSend[3]=0x06;
  Sum=Sum+DataSend[3];
  DataSend[4]=0x01; // BufferID (CharBuffer1 - 0x01)
  Sum=Sum+DataSend[4];
  // PageID (a sablon Flash helye, két bájt magas bájttal el?l és alacsony bájttal mögött)
  DataSend[5]= (uint8_t)(IDStore>> 8); 
  Sum=Sum+DataSend[5];
  DataSend[6]= (uint8_t)(IDStore&0xFF);
  Sum=Sum+DataSend[6];
  // Checksum (két bájt magas bájttal el?l és alacsony bájttal mögött)
  DataSend[7]=(uint8_t)(Sum >> 8);
  DataSend[8]=(uint8_t)(Sum&0xFF);
  for(int i=0; i<9; i++)
  {
    mySerial.write(DataSend[i]);
  }
}

void SendDeleteFinger(uint16_t IDDelete)
{
  uint16_t Sum=0;
  uint8_t DataSend[10]={0};

  DataSend[0]=0x01;
  Sum=Sum+DataSend[0];
  DataSend[1]=0x00;
  Sum=Sum+DataSend[1];
  DataSend[2]=0x07;
  Sum=Sum+DataSend[2];
  DataSend[3]=0x0C;
  Sum=Sum+DataSend[3];
  DataSend[4]=(uint8_t)(IDDelete>> 8);
  Sum=Sum+DataSend[4];
  DataSend[5]= (uint8_t) (IDDelete&0xFF);
  Sum=Sum+DataSend[5];
  DataSend[6]=0x00;
  Sum=Sum+DataSend[6];
  DataSend[7]=0x01;
  Sum=Sum+DataSend[7];
  DataSend[8]=(uint8_t)(Sum>> 8);
  DataSend[9]=(uint8_t)(Sum&0xFF);
  for(int i=0; i<10; i++)
  {
    mySerial.write(DataSend[i]);
  }
}

// A segít? függvények az UART-on keresztüli adatok fogadására az érzékel?t?l, és csomagokká történ? feldolgozásához
// packet a fogadott bájtokat tartalmazó struktúra.
uint8_t CheckFPRespsone(uint8_t MaxRead) // A "MaxRead" az összes fogadott bájt (az adatlapon olvasható)
{
  uint8_t ByteCount=0;
  uint8_t FPRXData[20]={0xFF};
  uint8_t UARTData[1]={0};
  uint8_t Result;
  IDFromFinger=0xFF;
  uint32_t TimeOut = millis();
  while(ByteCount<MaxRead && millis() - TimeOut < 1000)
  {
    delay(5);
    if(mySerial.available())
    {
      UARTData[0]= mySerial.read(); // fogadja az MCU-tól az érzékel?höz küldött minden egyes bájtot
      FPRXData[ByteCount] = UARTData[0];
      ByteCount++;
    }   
  }
  if(ByteCount==0)
  {
    Result=FP_ERROR;
    return Result;
  }
  else if(ByteCount<MaxRead)
  {
    Result=FP_ERROR;
    return Result;
  }
  else // valid data return
  {
    Result=FPRXData[9]; // confirmation code
    IDFromFinger=FPRXData[11]; // PageID 
    return Result;
  }
  
  return Result;
  
}

// Ezek az ujjlenyomat-modul néhány fontos funkciója
uint8_t GetNumberOfFinger()
{
  uint8_t Result;
  SendFPHeader();
  SendFGetNumberOfFinger();
  Result=CheckFPRespsone(14);
  if(Result!=FP_OK) return 0xFF;

  return IDFromFinger;
}



uint8_t CheckFinger()
{
  uint8_t Result=FP_NOFINGER;
  uint32_t TimeOut = millis();
  //SendFPHeader();
  //SendFPGetImage();
  //Result=CheckFPRespsone(12);
  //UART0_int(Result);
  //USART0_str("zzzzz\n\r");
  if(Result==FP_NOFINGER) 
  {
    SendFPHeader();
    SendFPGetImage();
    Result=CheckFPRespsone(12);
  }

  if(Result!=FP_OK)
  {
    return FP_ERROR;
  }
  //USART0_str("312321312312\n\r");
  // continue if detect finger;
  SendFPHeader();
  SendFPCreateCharFile1();
  Result=CheckFPRespsone(12);
  //UART0_int(Result);
  //USART0_str("\n\r");
  if(Result!=FP_OK)
  {
    return FP_ERROR;
  }

  // Search Finger
  SendFPHeader();
  SendFPDSearchFinger();
  Result=CheckFPRespsone(16); 
  return Result;
}

uint8_t Delete_ID(uint16_t ID)
{
  uint8_t Result;
  SendFPHeader();
  SendDeleteFinger(ID);
  Result=CheckFPRespsone(17);
  return Result;
}

uint8_t Empty_Database()
{
  uint8_t Result;
  SendFPHeader();
  SendFPDeleteAllFinger();
  Result=CheckFPRespsone(12);
  return Result;
}
