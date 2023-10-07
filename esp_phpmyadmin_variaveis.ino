//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Test Database Access 
//======================================== Including the libraries.
// #include <WiFiManager.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>
#include "DHT.h"

//======================================== DHT sensor settings (DHT11).
#define DHTPIN 4 //--> Defines the Digital Pin connected to the DHT11 sensor.
#define DHTTYPE DHT11 //--> Defines the type of DHT sensor used. Here used is the DHT11 sensor.
DHT dht11_sensor(DHTPIN, DHTTYPE); //--> Initialize DHT sensor.


//======================================== LEDs
#define ON_Board_LED 2 // Defines the Digital Pin of the "On Board LED".


// LED 01 -> Verde
// LED 02 -> Amarelo
// LED 03 -> Vermelho

#define Led_verde 16 // Defines GPIO 13 as LED_1. //checklist feito

#define Led_amarelo 17 // Defines GPIO 12 as LED_2. //tempo de troca das pecas

#define Led_vermelho 18 // Defines GPIO 12 as LED_2. //checklist nao feito


//======================================== SSID and Password of your WiFi router.
const char* ssid = "Rebeca";
const char* password = "Rebeca123";


//======================================== Variables for HTTP POST request data.
String postData = ""; //--> Variables sent for HTTP POST request data.
String payload = "";  //--> Variable for receiving response from HTTP POST.


//======================================== Variables for DHT11 sensor data.
float send_temperatura;
float send_velocidade;
float send_oleo_caixaDeVelocidade;
float send_viscosidade_caixaDeVelocidade;
float send_oleo_caixaDeNorton;
float send_viscosidade_caixaDeNorton;
float send_oleo_aventalDoTorno;
float send_viscosidade_aventalDoTorno;
float send_vibracao;
float send_tempo_On;
float send_tempo_Off;
float send_tempo;
// int send_Humd;
String send_Status_Read_DHT11 = "";


//======================================== Subroutine to control LEDs after successfully fetching data from database.
void control_LEDs() {
  Serial.println();
  Serial.println("---------------control_LEDs()");
  JSONVar myObject = JSON.parse(payload);

  //Verifica se o objeto não esta vazio- JSON.typeof(jsonVar) can be used to get the type of the var
  if (JSON.typeof(myObject) == "undefined") {
    Serial.println("Parsing input failed!");
    Serial.println("---------------");
    return;
  }

  //Verifica se o objeto possui o LED_01
  if (myObject.hasOwnProperty("Led_verde")) {
    Serial.print("myObject[\"Led_verde\"] = ");
    Serial.println(myObject["Led_verde"]);
    // myObject["Led_verde"] = "ON";
    // Serial.println(myObject["Led_verde"]);
  }

  //Verifica se o objeto possui o LED_01=2
  if (myObject.hasOwnProperty("Led_amarelo")) {
    Serial.print("myObject[\"Led_amarelo\"] = ");
    Serial.println(myObject["Led_amarelo"]);
  }

  //Verifica se o objeto possui o LED_01=3
  if (myObject.hasOwnProperty("Led_vermelho")) {
    Serial.print("myObject[\"Led_vermelho\"] = ");
    Serial.println(myObject["Led_vermelho"]);
  }

  if(strcmp(myObject["Led_verde"], "ON") == 0)   {digitalWrite(Led_verde, HIGH);  Serial.println("LED 01 ON"); }
  if(strcmp(myObject["Led_verde"], "OFF") == 0)  {digitalWrite(Led_verde, LOW);   Serial.println("LED 01 OFF");}
  if(strcmp(myObject["Led_amarelo"], "ON") == 0)   {digitalWrite(Led_amarelo, HIGH);  Serial.println("LED 02 ON"); }
  if(strcmp(myObject["Led_amarelo"], "OFF") == 0)  {digitalWrite(Led_amarelo, LOW);   Serial.println("LED 02 OFF");}
  if(strcmp(myObject["Led_vermelho"], "ON") == 0)   {digitalWrite(Led_vermelho, HIGH);  Serial.println("LED 02 ON"); }
  if(strcmp(myObject["Led_vermelho"], "OFF") == 0)  {digitalWrite(Led_vermelho, LOW);   Serial.println("LED 02 OFF");}

  Serial.println("---------------");
}


// _________________________________________________________ Subroutine to read and get data from the DHT11 sensor.
void get_DHT11_sensor_data() {
  Serial.println();
  Serial.println("-------------get_DHT11_sensor_data()");
  
  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  
  // Read temperature as Celsius (the default)
  // send_Temp = 24;//dht11_sensor.readTemperature();
  send_temperatura = 1;
  send_velocidade = 2;
  send_oleo_caixaDeVelocidade = 3;
  send_viscosidade_caixaDeVelocidade = 4;
  send_oleo_caixaDeNorton = 5;
  send_viscosidade_caixaDeNorton = 6;
  send_oleo_aventalDoTorno = 7;
  send_viscosidade_aventalDoTorno = 8;
  send_vibracao = 9;
  send_tempo_On = 10;
  send_tempo_Off = 11;
  send_tempo_On = send_tempo_Off - send_tempo_On;
  
  // Read Humidity
  // send_Humd = 75;//dht11_sensor.readHumidity();
  
  // Read temperature as Fahrenheit (isFahrenheit = true)
  // float ft = dht11_sensor.readTemperature(true);

  // Check if any reads failed.
  if (isnan(send_temperatura) /*|| isnan(send_velocidade) || isnan(send_oleo_caixaDeVelocidade) || isnan(send_viscosidade_caixaDeVelocidade) || isnan(send_oleo_caixaDeNorton) || isnan(send_viscosidade_caixaDeNorton) || isnan(send_oleo_aventalDoTorno) || isnan(send_viscosidade_aventalDoTorno) || isnan(send_vibracao) || isnan(send_tempo_On) || isnan(send_tempo_Off)*/) {
    Serial.println("Failed to read from DHT sensor!");
     send_temperatura = 0.0;
      send_velocidade = 0.0;
      send_oleo_caixaDeVelocidade = 0.0;
      send_viscosidade_caixaDeVelocidade = 0.0;
      send_oleo_caixaDeNorton = 0.0;
      send_viscosidade_caixaDeNorton = 0.0;
      send_oleo_aventalDoTorno = 0.0;
      send_viscosidade_aventalDoTorno = 0.0;
      send_vibracao = 0.0;
      send_tempo_On = 0.0;
      send_tempo_Off = 0.0;
      send_tempo_On = send_tempo_Off - send_tempo_On;
    send_Status_Read_DHT11 = "FAILED";
  } else {
    send_Status_Read_DHT11 = "SUCCEED";
  }
  
  Serial.printf("Temperatura : %.2f °C\n", send_temperatura);
  Serial.printf("Velocidade : %d %%\n", send_velocidade);
  Serial.printf("Oleo caixa De Velocidade: %d %%\n", send_oleo_caixaDeVelocidade);
  Serial.printf("Viscosidade caixa De Velocidade: %d %%\n", send_viscosidade_caixaDeVelocidade);
  Serial.printf("Oleo caixa De Norton: %d %%\n", send_oleo_caixaDeNorton);
  Serial.printf("Viscosidade caixa De Norton: %d %%\n", send_viscosidade_caixaDeNorton);
  Serial.printf("Oleo avental Do Torno: %d %%\n", send_oleo_aventalDoTorno);
  Serial.printf("Viscosidade avental Do Torno: %d %%\n", send_viscosidade_aventalDoTorno);
  Serial.printf("Vibracao: %d %%\n", send_vibracao);
  Serial.printf("Tempo On: %d %%\n", send_tempo_On);
  Serial.printf("Tempo Off: %d %%\n", send_tempo_Off);
  Serial.printf("Status Read DHT11 Sensor : %s\n", send_Status_Read_DHT11);
  Serial.println("------------------------------");
}


//______________________________________________ VOID SETUP() -C onexao
void setup() {
  // put your setup code here, to run once:
  
  Serial.begin(115200); //--> Initialize serial communications with the PC.

  pinMode(ON_Board_LED,OUTPUT); //--> On Board LED port Direction output.
  pinMode(Led_verde,OUTPUT); //--> LED_01 port Direction output.
  pinMode(Led_amarelo,OUTPUT); //--> LED_02 port Direction output.
  pinMode(Led_vermelho,OUTPUT); //--> LED_03 port Direction output.
  
  digitalWrite(ON_Board_LED, HIGH); //--> Turn on Led On Board.
  digitalWrite(Led_verde, HIGH); //--> Turn on LED_01.
  digitalWrite(Led_amarelo, HIGH); //--> Turn on LED_02.
  digitalWrite(Led_vermelho, HIGH); //--> Turn on LED_03.

  delay(2000);

  digitalWrite(ON_Board_LED, LOW); //--> Turn off Led On Board.
  digitalWrite(Led_verde, LOW); //--> Turn off Led LED_01.
  digitalWrite(Led_amarelo, LOW); //--> Turn off Led LED_02.
  digitalWrite(Led_vermelho, LOW); //--> Turn off Led LED_03.

  //---------------------------------------- Make WiFi on ESP32 in "STA/Station" mode and start connecting to WiFi Router/Hotspot.
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  
  Serial.println();
  Serial.print("-------------Connecting-------------");

  //---------------------------------------- The process of connecting the WiFi on the ESP32 to the WiFi Router/Hotspot.
  // The process timeout of connecting ESP32 with WiFi Hotspot / WiFi Router is 20 seconds.
  // If within 20 seconds the ESP32 has not been successfully connected to WiFi, the ESP32 will restart.
  // I made this condition because on my ESP32, there are times when it seems like it can't connect to WiFi, so it needs to be restarted to be able to connect to WiFi.

  int connecting_process_timed_out = 20; //--> 20 = 20 seconds.
  connecting_process_timed_out = connecting_process_timed_out * 2;
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    //........................................ Make the On Board Flashing LED on the process of connecting to the wifi router.
    digitalWrite(ON_Board_LED, HIGH);
    delay(250);
    digitalWrite(ON_Board_LED, LOW);
    delay(250);
    

    //........................................ Countdown "connecting_process_timed_out".
    if(connecting_process_timed_out > 0) connecting_process_timed_out--;
    if(connecting_process_timed_out == 0) {
      delay(1000);
      ESP.restart();
    }
  }
 
  
  digitalWrite(ON_Board_LED, LOW); //--> Turn off the On Board LED when it is connected to the wifi router.
  
  //---------------------------------------- If successfully connected to the wifi router, the IP Address that will be visited is displayed in the serial monitor
  Serial.println();
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  //Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println("-------------");
  //---------------------------------------- 

  // Setting up the DHT sensor (DHT11).
  dht11_sensor.begin();
  delay(2000);
}
 

//________________________________________________________________________________ VOID LOOP()
void loop() {
  // put your main code here, to run repeatedly

  //---------------------------------------- Check WiFi connection status.
  if(WiFi.status()== WL_CONNECTED) {
    HTTPClient http;  //--> Declare object of class HTTPClient.
    int httpCode;     //--> Variables for HTTP return code.
    
    //........................................ Get LEDs data from database to control LEDs.
    postData = "id=esp32_01&id_maquina=1";
    payload = "";
  
    digitalWrite(ON_Board_LED, HIGH);
    Serial.println();
    Serial.println("---------------getdata.php");
    // The order of the folders I recommend:
    // xampp\htdocs\your_project_folder_name\phpfile.php


    // ESP32 accesses the data bases at this line of code: 
    //#define REPLACE_WITH_YOUR_COMPUTER_IP_ADDRESS = 192.168.56.1;

    // http.begin("http://REPLACE_WITH_YOUR_COMPUTER_IP_ADDRESS/REPLACE_WITH_PROJECT_FOLDER_NAME_IN_htdocs_FOLDER/getdata.php");
    // Example : http.begin("http://192.168.56.1/tcc/getdata.php");
    http.begin("http://192.168.76.214/tcc/getdata.php");  //--> Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");        //--> Specify content-type header
   
    httpCode = http.POST(postData); //--> Send the request
    payload = http.getString();     //--> Get the response payload
  
    Serial.print("httpCode : ");
    Serial.println(httpCode); //--> Print HTTP return code
    Serial.print("payload  : ");
    Serial.println(payload);  //--> Print request response payload
    
    http.end();  //--> Close connection
    Serial.println("---------------");
    digitalWrite(ON_Board_LED, LOW);
    //........................................ 

    // Calls the control_LEDs() subroutine.
    control_LEDs();
    
    delay(1000);

    // Calls the get_DHT11_sensor_data() subroutine.
    get_DHT11_sensor_data();
  
    //........................................ The process of sending the DHT11 sensor data to the database.
    postData = "id=esp32_01";
    postData += "&temperatura=" + String(send_temperatura);
    postData += "&velocidade=" + String(send_velocidade);
    postData += "&oleo_caixaDeVelocidade=" + String(send_oleo_caixaDeVelocidade);
    postData += "&viscosidade_caixaDeVelocidade=" + String(send_viscosidade_caixaDeVelocidade);
    postData += "&oleo_caixaDeNorton=" + String(send_oleo_caixaDeNorton);
    postData += "&viscosidade_caixaDeNorton=" + String(send_viscosidade_caixaDeNorton);
    postData += "&oleo_aventalDoTorno=" + String(send_oleo_aventalDoTorno);
    postData += "&viscosidade_aventalDoTorno=" + String(send_viscosidade_aventalDoTorno);
    postData += "&vibracao=" + String(send_vibracao);
    postData += "&tempo_On=" + String(send_tempo);
    postData += "&status_read_sensor_dht11=" + send_Status_Read_DHT11;
    
    payload = "";
  
    digitalWrite(ON_Board_LED, HIGH);
    Serial.println();
    Serial.println("---------------updateDHT11data.php");
    // Example : http.begin("http://192.168.56.1/tcc/updateDHT11data.php");
    http.begin("http://192.168.76.214/tcc/updateDHT11data.php");  //--> Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");  //--> Specify content-type header
   
    httpCode = http.POST(postData); //--> Send the request
    payload = http.getString();  //--> Get the response payload
  
    Serial.print("httpCode : ");
    Serial.println(httpCode); //--> Print HTTP return code
    Serial.print("payload  : ");
    Serial.println(payload);  //--> Print request response payloadkj
    
    http.end();  //Close connection
    Serial.println("---------------");
    digitalWrite(ON_Board_LED, LOW);
    
    delay(5000);
  }
}

