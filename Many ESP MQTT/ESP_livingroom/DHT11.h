#include <Arduino.h>

static uint8_t DHT11Data[5] = {0};
static uint8_t DHT11Init = 0;
static uint8_t I_H, D_H, I_Temp, D_Temp;
  
void DHT11Setup(){
  // Wait for the sensor to stabilise on power on
  //_delay_ms(2000);
  
  // Set DDR to output
  pinMode(D3, OUTPUT);
  
  // Set setup flag
  //DHT11Init = 1;
}


uint8_t DHT11ReadData(){
  uint8_t sensor_bytes, bits, buffer=0, timeout=0, checksum;
  
  /* Initialise sensor if flag is 0 then set to 1 to run only once */
  //if(DHT11Init == 0) DHT11Setup();
  
  /* Send START signal to sensor */
  //SENSOR_DDR |= (1 << SENSOR_PIN_BIT); // set pin to output
  pinMode(D3, OUTPUT);
  //SENSOR_PORT &= ~(1 << SENSOR_PIN_BIT); // set pin LOW
  digitalWrite(D3, LOW);
  delay(18); // keep pin LOW for at least 18 ms

  /* Set DDR to input LOW (high Z) to read data from sensor. 
  The external pull-up resistor will pull the data line HIGH */
  //SENSOR_DDR &= ~(1 << SENSOR_PIN_BIT);
  pinMode(D3, INPUT_PULLUP);
  delayMicroseconds(20); // wait for 20-40 us
  
  /* Listen for sensor response - 80us LOW and 80us HIGH signal */
  if(digitalRead(D3) == HIGH){ // If HIGH, sensor didn't respond
    return 0; // error code
  }
  
  /* Sensor sent LOW signal, wait for HIGH */
  delayMicroseconds(82);
  
  /* If HIGH, sensor is ready to send data */
  if(digitalRead(D3) == HIGH){
    delayMicroseconds(82); // wait for HIGH signal to end
    if(digitalRead(D3) == HIGH) return 0; // still HIGH - something is wrong
  }else{
    return 0; // error code
  }
  
  /* Ready to read data from sensor */
  for(sensor_bytes=0; sensor_bytes<5; sensor_bytes++)
  {
    /* Reset the buffer */
    buffer = 0;
  
    for(bits=0; bits<8; bits++)
    {
      /* Wait 50 us between each bits while signal is LOW */
      while(digitalRead(D3) == LOW){
        /* Wait no more than 80 us. If the sensor breaks and remains LOW
        the MCU will not remain stuck in a while loop */
        timeout++;
        if(timeout > 5) break;
        delayMicroseconds(10);
      }
      timeout = 0;
      
      /* Signal is HIGH - read the bit */
      if(digitalRead(D3) == HIGH)
      {
        delayMicroseconds(40); // 26-28 us HIGH means a 0 bit, 70 us means a 1 bit
        /* If signal is still HIGH means a 1 bit */
        if(digitalRead(D3) == HIGH){
          /* Put a 1 to buffer. Sensor sends MSB first */
          buffer |= 1 << (7-bits);
        }
        
        /* Wait for HIGH signal to end */
        while(digitalRead(D3) == HIGH)
        {
          timeout++;
          if(timeout >5) break; 
          delayMicroseconds(10);
        }
        timeout = 0;
        while(~digitalRead(D3) == LOW);
      }
      else
      {
        return 0; // signal still LOW. Return error response
      }
    }
    
    /* Dump the buffer to global array */
    DHT11Data[sensor_bytes] = buffer;
  }
  
  I_H = DHT11Data[0];
  D_H = DHT11Data[1];
  I_Temp = DHT11Data[2];
  D_Temp = DHT11Data[3];
  /* Check for data transmission errors */
  checksum = I_H + D_H + I_Temp + D_Temp;
  if(checksum != DHT11Data[4]){
    return -1; // checksum error code
  }
  
  /* OK return code */
  return 1;
}
