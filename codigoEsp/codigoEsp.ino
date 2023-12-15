// Incluindo bibliotecas necessárias
#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>
#include "DHT.h"

// Definindo o pino do sensor DHT e seu tipo
#define DHTPIN 18
#define DHTTYPE DHT11
DHT dht11_sensor(DHTPIN, DHTTYPE);

// Definindo os pinos dos LEDs
#define ON_Board_LED 2
#define led_vermelho 17
#define led_amarelo 16
#define led_verde 4

// Informações da rede WiFi
const char* ssid = "Mi 9T Pro";//"Galaxy A125CE6";//"Mi 9T Pro";
const char* password = "e18ff26ed35ba";//"pclb8148";//"e18ff26ed35ba";

// Strings para armazenar dados a serem enviados ao servidor
String postData = "";
String payload = "";
String send_Status_Read_DHT11 = "";

// Variáveis para armazenar dados do sensor e do checklist
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
/*
int checklist_item_1_state;
int checklist_item_2_state;*/

// Sub-rotina para controlar os LEDs com base nos dados recebidos do servidor
void control_LEDs() {
  Serial.println();
  Serial.println("---------------control_LEDs()");
  JSONVar myObject = JSON.parse(payload);

  if (JSON.typeof(myObject) == "undefined") {
    Serial.println("Parsing input failed!");
    Serial.println("---------------");
    return;
  }

  // Verificando se o objeto JSON contém dados para cada LED
  if (myObject.hasOwnProperty("led_vermelho")) {
    Serial.print("myObject[\"led_vermelho\"] = ");
    Serial.println(myObject["led_vermelho"]);
  }

  if (myObject.hasOwnProperty("led_amarelo")) {
    Serial.print("myObject[\"led_amarelo\"] = ");
    Serial.println(myObject["led_amarelo"]);
  }

  if (myObject.hasOwnProperty("led_verde")) {
    Serial.print("myObject[\"led_verde\"] = ");
    Serial.println(myObject["led_verde"]);
  }

  // Controlando os LEDs com base nos dados recebidos do servidor
  if (strcmp(myObject["led_vermelho"], "ON") == 0) {
    digitalWrite(led_vermelho, HIGH);
    Serial.println("LED VERMELHO ON");
  } else if (strcmp(myObject["led_vermelho"], "OFF") == 0) {
    digitalWrite(led_vermelho, LOW);
    Serial.println("LED VERMELHO OFF");
  }

  if (strcmp(myObject["led_amarelo"], "ON") == 0) {
    digitalWrite(led_amarelo, HIGH);
    Serial.println("LED AMARELO ON");
  } else if (strcmp(myObject["led_amarelo"], "OFF") == 0) {
    digitalWrite(led_amarelo, LOW);
    Serial.println("LED AMARELO OFF");
  }

  if (strcmp(myObject["led_verde"], "ON") == 0) {
    digitalWrite(led_verde, HIGH);
    Serial.println("LED VERDE ON");
  } else if (strcmp(myObject["led_verde"], "OFF") == 0) {
    digitalWrite(led_verde, LOW);
    Serial.println("LED VERDE OFF");
  }

  // Lógica do checklist
  bool checklist_ok = true;

  // Verificando os dados do checklist e ajustando a variável checklist_ok conforme necessário
  // Trocar os valores por valores corretos após teste
  /*if (checklist_item_1_state != valor_correto_item_1 || checklist_item_2_state != valor_correto_item_2) {
    checklist_ok = false;
  }*/

  // Controlando os LEDs com base nos resultados do checklist
  /*if (checklist_ok) {
    digitalWrite(led_verde, LOW);  // LED verde
    digitalWrite(led_vermelho, HIGH); // Desligar LED vermelho
  } else {
    digitalWrite(led_verde, HIGH); // Desligar LED verde
    digitalWrite(led_vermelho, LOW);  // LED vermelho
  }*/

  Serial.println("---------------");
}

// Sub-rotina para obter dados do sensor DHT
/*void get_DHT11_sensor_data() {
  Serial.println();
  Serial.println("-------------get_DHT11_sensor_data()");*/

  // _________________________________________________________ Sub-rotina para ler e obter dados do sensor DHT11.
  void get_DHT11_sensor_data() {
    Serial.println();
    Serial.println("-------------get_DHT11_sensor_data()");
  
    // A leitura da temperatura ou umidade leva cerca de 250 milissegundos!
    // As leituras do sensor também podem ter até 2 segundos "antigos" (é um sensor muito lento)
  
    // Ler temperatura em Celsius (o padrão)
    // send_Temp = 24;//dht11_sensor.readTemperature();
    send_temperatura = rand() % 101;
    send_velocidade = rand() % 101;
    send_oleo_caixaDeVelocidade = rand() % 101;
    send_viscosidade_caixaDeVelocidade = rand() % 101;
    send_oleo_caixaDeNorton = rand() % 101;
    send_viscosidade_caixaDeNorton = rand() % 101;
    send_oleo_aventalDoTorno = rand() % 101;
    send_viscosidade_aventalDoTorno = rand() % 101;
    send_vibracao = rand() % 101;

    //to do: ver como vai medir o tempo
    send_tempo_On = 10;
    send_tempo_Off = 11;
    send_tempo_On = send_tempo_Off - send_tempo_On;
  
    // Ler temperatura em Fahrenheit (isFahrenheit = true)
    // float ft = dht11_sensor.readTemperature(true);
  
    // Verificar se alguma leitura falhou.
    if (isnan(send_temperatura) || isnan(send_velocidade) || isnan(send_oleo_caixaDeVelocidade) || isnan(send_viscosidade_caixaDeVelocidade) || isnan(send_oleo_caixaDeNorton) || isnan(send_viscosidade_caixaDeNorton) || isnan(send_oleo_aventalDoTorno) || isnan(send_viscosidade_aventalDoTorno) || isnan(send_vibracao) || isnan(send_tempo_On) || isnan(send_tempo_Off)) {
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
        //to do: ver como vai medir o tempo
        send_tempo_On = 0.0;
        send_tempo_Off = 0.0;
        send_tempo_On = send_tempo_Off - send_tempo_On;

        send_Status_Read_DHT11 = "FAILED";
    } else {
      send_Status_Read_DHT11 = "SUCCEED";
    }
  
    Serial.printf("Temperatura : %.2f °C\n", send_temperatura);
    Serial.printf("Velocidade : %.2f %\n", send_velocidade);
    Serial.printf("Oleo caixa De Velocidade: %.2f %\n", send_oleo_caixaDeVelocidade);
    Serial.printf("Viscosidade caixa De Velocidade: %.2f %\n", send_viscosidade_caixaDeVelocidade);
    Serial.printf("Oleo caixa De Norton: %.2f %\n", send_oleo_caixaDeNorton);
    Serial.printf("Viscosidade caixa De Norton: %.2f %\n", send_viscosidade_caixaDeNorton);
    Serial.printf("Oleo avental Do Torno: %.2f %\n", send_oleo_aventalDoTorno);
    Serial.printf("Viscosidade avental Do Torno: %.2f %\n", send_viscosidade_aventalDoTorno);
    Serial.printf("Vibracao: %.2f %\n", send_vibracao);
    //to do: ver como vai medir o tempo
    Serial.printf("Tempo On: %.2f %\n", send_tempo_On);
    Serial.printf("Tempo Off: %.2f %\n", send_tempo_Off);
    Serial.printf("Status Read DHT11 Sensor : %s\n", send_Status_Read_DHT11);
    Serial.println("------------------------------");
  }

  /*Serial.println("------------------------------");
}*/

void setup() {
  // Inicializando a comunicação serial e configurando os pinos dos LEDs
  Serial.begin(115200);
  pinMode(ON_Board_LED, OUTPUT);
  pinMode(led_vermelho, OUTPUT);
  pinMode(led_amarelo, OUTPUT);
  pinMode(led_verde, OUTPUT);

  // Desligando os LEDs inicialmente
  digitalWrite(ON_Board_LED, HIGH);
  digitalWrite(led_vermelho, HIGH);
  digitalWrite(led_amarelo, HIGH);
  digitalWrite(led_verde, HIGH);

  delay(2000);

  digitalWrite(ON_Board_LED, LOW);
  digitalWrite(led_vermelho, LOW);
  digitalWrite(led_amarelo, LOW);
  digitalWrite(led_verde, LOW);

  // Configurando a conexão WiFi
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.println();
  Serial.print("-------------Conectando-------------");

  // Temporizador para evitar espera infinita
  int connecting_process_timed_out = 20;
  connecting_process_timed_out = connecting_process_timed_out * 2;
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    digitalWrite(ON_Board_LED, HIGH);
    delay(250);
    digitalWrite(ON_Board_LED, LOW);
    delay(250);

    if(connecting_process_timed_out > 0) connecting_process_timed_out--;
    if(connecting_process_timed_out == 0) {
      delay(1000);
      ESP.restart();
    }
  }

  digitalWrite(ON_Board_LED, LOW);

  Serial.println();
  Serial.print("Conectado com sucesso a : ");
  Serial.println(ssid);
  Serial.println(WiFi.localIP());
  Serial.println("-------------");

  // Inicializando o sensor DHT
  dht11_sensor.begin();
  delay(2000);
}

void loop() {
  // Verificando se está conectado à rede WiFi
  if(WiFi.status() == WL_CONNECTED) {
    
    HTTPClient http;
    int httpCode;



//........................................ Não é para apagar.
    postData = "esp=esp32_01";
    postData += "&id_maquina=1";
    payload = "";
  
    digitalWrite(ON_Board_LED, HIGH);
    Serial.println();
    Serial.println("---------------getdata.php");
   
   
    // http.begin("http://192.168.110.214/iot/getdata.php");
    http.begin("https://192.168.41.214/iot/getdata.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
   
    httpCode = http.POST(postData);
    payload = http.getString();
  
    Serial.print("httpCode : ");
    Serial.println(httpCode);
    Serial.print("payload  : ");
    Serial.println(payload);
    
    http.end();  //--> Close connection
    Serial.println("---------------");
    digitalWrite(ON_Board_LED, LOW);

    // Calls the control_LEDs() subroutine.
    control_LEDs();
    
    delay(1000);

    // Calls the get_DHT11_sensor_data() subroutine.
    get_DHT11_sensor_data();
  
    //........................................ The process of sending the DHT11 sensor data to the database.






    // Montando os dados a serem enviados ao servidor
    postData = "id=esp32_01";
    postData  += "&id_maquina=1"; //definir maquina
    postData += "&temperatura=" + String(send_temperatura);
    postData += "&velocidade=" + String(send_velocidade);
    postData += "&oleo_caixaDeVelocidade=" + String(send_oleo_caixaDeVelocidade);
    postData += "&viscosidade_caixaDeVelocidade=" + String(send_viscosidade_caixaDeVelocidade);
    postData += "&oleo_caixaDeNorton=" + String(send_oleo_caixaDeNorton);
    postData += "&viscosidade_caixaDeNorton=" + String(send_viscosidade_caixaDeNorton);
    postData += "&oleo_aventalDoTorno=" + String(send_oleo_aventalDoTorno);
    postData += "&viscosidade_aventalDoTorno=" + String(send_viscosidade_aventalDoTorno);
    postData += "&vibracao=" + String(send_vibracao);
    postData += "&status_read_sensor_dht11=" + String(send_Status_Read_DHT11);

    /*postData += "&item_1=" + String(checklist_item_1_state);
    postData += "&item_2=" + String(checklist_item_2_state);*/

    payload = "";

    // Acendendo o LED indicador de envio
    digitalWrite(ON_Board_LED, HIGH);
    Serial.println();
    Serial.println("---------------updateDHT11data.php");
    // http.begin("http://192.168.110.214/iot/updateDHT11data.php");
    http.begin("https://192.168.41.214/iot/updateDHT11data.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Enviando os dados ao servidor
    httpCode = http.POST(postData);
    payload = http.getString();

    Serial.print("httpCode UP : ");
    Serial.println(httpCode);
    Serial.print("payload UP : ");
    Serial.println(payload);

    http.end();  //Close connection
    Serial.println("---------------");
    digitalWrite(ON_Board_LED, LOW);

    //5 segundos
    delay(5000);
  }
}
