```markdown
﻿![Machine Learning Battle](https://github.com/anfibil/classification-battle/raw/master/src/img/logo.png)

# Batalla de Aprendizaje Automático

Aplicación web simple que te permite ejecutar y rastrear un desafío de ML en tiempo real, similar a Kaggle.

## Cómo ejecutar

Para ejecutar el proyecto, solo necesitas ejecutar el siguiente comando:
```
docker-compose up  
```
## Captura de pantalla
<p align="center">
  <img alt="Batalla de Clasificación" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot1.png">  <br>
</p>

## Cómo crear conjuntos de datos
Los conjuntos de datos deben colocarse dentro de la carpeta 'datasets', donde cada uno debe tener 2 archivos. NAME.x.npy y NAME.y.npy. Por favor, consulta el archivo temp/create-dataset.py para aprender cómo crear un nuevo conjunto de datos.

La carpeta de uploads se utilizará para almacenar temporalmente los modelos enviados.

## Cómo limpiar la base de datos
La única forma de eliminar los resultados enviados es editando manualmente el archivo "database" 'super-secure-database.json'. Diviértete con eso ;)

## Cómo los estudiantes subirán sus modelos
Los estudiantes deben subir los modelos serializados utilizando la biblioteca joblib. Por favor, consulta el archivo temp/create-model.py para ver cómo los estudiantes deben crear los modelos:
```
from joblib import dump
dump(clf_lr, 'model.joblib')
```
Para subir el modelo, el estudiante solo necesita usar el formulario "Enviar Modelo":

<p align="center">
  <img alt="Subir Modelo" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot2.png"> 
</p>
```