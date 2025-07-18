# Previsão do Nível do Rio em Rio do Sul

Este projeto utiliza dados reais de nível do rio e chuvas para prever o nível do rio em Rio do Sul (SC) usando regressão linear. Inclui um pipeline completo de ciência de dados em Python (Jupyter Notebook), um backend em Python para inferência, uma aplicação web em PHP e ambiente Dockerizado com Nginx.

## Estrutura do Projeto
- `Previsao.ipynb`: Notebook com todo o pipeline de análise, modelagem e avaliação.
- `dados.csv`: Dataset real utilizado para o treinamento.
- `predict.py`: Script Python para inferência do modelo treinado.
- `app_previsao.php`: Aplicação web para entrada de dados e visualização da previsão.
- `modelo_nivel_rio.joblib`: Modelo treinado salvo.
- `Dockerfile`, `docker-compose.yml`, `nginx.conf`: Infraestrutura para rodar o projeto em ambiente Docker.
- `requirements.txt`: Dependências Python.

## Como rodar o projeto

### Pré-requisitos
- Docker e Docker Compose instalados

### Passos
1. Clone o repositório:
   ```sh
   git clone https://github.com/FelipeTMachado/previsaotempo.git
   cd previsaotempo
   ```
2. Suba o ambiente com Docker Compose:
   ```sh
   docker-compose up --build
   ```
3. Acesse a aplicação web em [http://localhost:8080](http://localhost:8080)

### Teste via terminal
Você pode testar a API diretamente com curl:
```sh
curl -X POST -H 'Content-Type: application/json' -d '{"data":{"NívelItuporanga":65,"ChuvaItuporanga":12,"NívelTaió":72,"ChuvaTaió":8}}' http://localhost:8080/
```

## Observações importantes
- Os níveis dos rios devem ser informados em metros na interface web, mas são convertidos para decímetros internamente.
- As chuvas são informadas em milímetros.
- O modelo atual é didático e pode apresentar previsões irreais em situações extremas. Veja a discussão no final do notebook para limitações e sugestões de melhoria.

## Licença
MIT

## Projeto completo
O código-fonte completo está disponível em: https://github.com/FelipeTMachado/previsaotempo
