![Machine Learning Battle](https://github.com/anfibil/classification-battle/raw/master/src/img/logo.png)

# Machine Learning Battle

Simple web application that allows you to run and track a simple, real-time, kaggle-like ML challenge.

## How to run

To run the project you just need to execute the command below:
```
docker-compose up  
```
## Screenshot
<p align="center">
  <img alt="Classification Battle" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot1.png">  <br>
</p>

## How to create datasets
The datasets need to be placed inside the folder 'datasets' where each one must have 2 files. NAME.x.npy and NAME.y.npy. Please check the file temp/create-dataset.py to learn how to create a new dataset.

The uploads folder will be used to store temporarily the models submitted.

## How to clear the database
The only way to delete submitted results is by manually editing the "database" file 'super-secure-database.json'. Have fun with that ;)

## How students will upload their models
Students need to upload the models serialized using the library joblib. please check the file temp/create-model.py to see how the students need to create the models:
```
from joblib import dump
dump(clf_lr, 'model.joblib')
```
To upload the model the student just need to use the "Submit Model" form:

<p align="center">
  <img alt="Upload Model" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot2.png"> 
</p>
