<h1>Monitoriamento IOT</h1>
<p>Acesse o projeto atravez de:</p>
<p>https://workprojectgrup.000webhostapp.com/iot/index.html</p>
<br>
<p>Esse projeto foi criado pensando no ambiente de aprendizagem do SENAI, onde os alunos utilizam de máquinas como Tornos, Frezadoras, CNCs e outros equipamentos</p>
<p>O objetivo do projeto é fornecer uma plataforma que permita o controle, monitoriamento e resgistros da utilização do maquinário</p>

<h2>Plataforma</h2>
<div align="center">
  <img src="https://github.com/JessicaMoreiraS/Portifolio/assets/100448388/87027242-e1f6-4fc8-87b4-1534ca248d66" width="80%">
</div>
<p>A plataforma conta com uma interface amigavel, permitindo a utilização por quatro níveis de acesso:</p>
<ol>
  <li>Gestor Padrão</li>
  <li>Gestor</li>
  <li>Professor</li>
  <li>Aluno</li>
</ol>

<h3>Gestor Padrão</h3>
<p>Este usuário possui a funcionalidade exclusiva de criar, visualizar e deletar gestores</p>
<div align="center">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/f7102f29-17b0-4784-b533-074d2d7d512d" width="70%">
</div>

<h3>Gestor</h3>
<p>Os gestores possuem acesso ao painel administrativo onde podem encontrar a opção de visualizar e deletar alunos, turmas, professores e equipamentos, bem como cadastrar novos professores e maquinários</p>
<div align="center" width="70%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/9b9b43a2-2046-4991-bec2-52db831e5b63" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/438a0e86-52fd-49ed-9928-186755909918" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/7368f60c-5615-4d2e-80d1-fb9873e60eec" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/c87483e6-a2a5-4401-9668-c340a0823e0f" width="35%">
</div>

<h3>Professor</h3>
<p>Como professor, o usuário poderá criar novas turma, essas turmas teram seus próprios códigos de acesso para que os alunos possam ingressar e suas respectivas máquinas altorizadas pelo professor, sendo assim os alunos somente poderam utilizar as máquinas altorizadas pelo professor para aquela turma, alem disso o professor também possui acesso as máquinas</p>
<div align="center" width="70%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/b147c624-4ed0-4aa1-bbad-9f3dd9766b9d" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/ec86a45b-7aed-4eb7-b277-1a66b46e39e7" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/21d04bdb-840b-4d8c-838f-6afc9074f0eb" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/092116b5-f275-4070-8f49-c610a43bd298" width="35%">
</div>

<h3>Aluno</h3>
<p>Acessando como aluno o usuário poderá acessar uma turma atravez do código passado pelo professor, dentro da turma o aluno deve selecionar a maáquina que irá utilizar e realizar a verficação do checklist</p>
<div align="center" width="70%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/cc4cd639-54ed-44fd-97d2-d788e9ef107e" width="35%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/13f594da-3997-446c-830b-39cc2cea1eb7" width="35%">
</div>

<h2>Checklist</h2>
<p>Os itens para o checklist podem ser de checagem manual ou altomatica atravez do microcontrolador incorporado ao máquinário</p>
<p>Os itens checados pelo microcontrador retornam os valores indicados pelos sensores e não podem ser alterados pelo usuario, isso significa que se os valores retornam dentro do limite e segurança os itens ficam altomaticamente altorizados, do contrario os itens ficam invalidos e o usuário não conseguirá enviar o checklist, já os itens de checagem manual são habilitados pelo proprio usuário</p>
<div align="center" width="70%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/33c297fa-5ab3-4a43-a7c6-84efc5a4fe38" width="48%">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/cd6ca171-2937-4c83-aae8-36fbd797b240" width="48%">
</div>
<p>Após a realização do checklist a máquina estará habilitada ao uso pelas proximas 4 horas, após esse periodo será necessaria uma nova checagem, isso será sinalizado ao usuário atravez da troca entre as cores verde e vermelha, verde sinalizando que a máquina pode ser utilizada ou vermelho indicando que a máquina não pode ser utlizada, tanto junto a maquina na plataforma online quanto de forma fizica com os LEDs conectados ao microcontrolador</p>
<p>Além disso, todos os checklists realizados são salvos na base de dados contem a máquina que foi utilizada, quando e qual usuário a utilizou</p>

<h3>Monitoriamento do maquinário com ESP32</h3>
<p>O monitoriamento ocorre atravez do microcontrolador ESP32, o circuito é montado conectando o ESP32 aos LEDs que fornecerão o alerta do status da máquina ao usuário, e aos respectivos sensores, porém, para demonstração foi utilizado um Encoder rotativos para simulação dos dados enviados pelos sensores</p>
<div align="center">
  <img src="https://github.com/JessicaMoreiraS/tcc/assets/100448388/f03d67d0-8fae-4de2-8aae-80e105bd646d"width="60%">
  <br>
  <sub>Referência da imagem: https://www.elektrobot.net/esp32-kullanimi-arduino-ile-programlama/</sub>
</div>
<p>O microncontrolador atualiza os valores constantemente e em caso de valores que ultrapassem os valores de segurança a máquina sinalizará ao usuario, atrazes da troca de cores, tanto na plataforma como com os LEDs, que a máquina não esta mais habilitada para o uso</p>
<p>Na versão disponivel com o link no inicio desse material os valores se mantém constantes já que o ESP32 encontra-se desligado</p>




