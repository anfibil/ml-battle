# ![Classification Battle](https://github.com/anfibil/classification-battle/raw/master/src/img/logo.png)

A website that stores the scores of classification tasks from several students.

## How to run

To run the project you just need to execute the command below:
```
docker-composer up
```
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