```markdown
![Machine Learning Battle](https://github.com/anfibil/classification-battle/raw/master/src/img/logo.png)

# 機械学習バトル

シンプルなウェブアプリケーションで、シンプルでリアルタイムなKaggleのようなMLチャレンジを実行および追跡できます。

## 実行方法

プロジェクトを実行するには、以下のコマンドを実行するだけです：
```
docker-compose up  
```
## スクリーンショット
<p align="center">
  <img alt="Classification Battle" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot1.png">  <br>
</p>

## データセットの作成方法
データセットは「datasets」フォルダー内に配置する必要があり、それぞれに2つのファイルが必要です。NAME.x.npyとNAME.y.npy。新しいデータセットを作成する方法については、temp/create-dataset.pyファイルを確認してください。

アップロードフォルダーは、提出されたモデルを一時的に保存するために使用されます。

## データベースをクリアする方法
提出された結果を削除する唯一の方法は、「super-secure-database.json」という「データベース」ファイルを手動で編集することです。それを楽しんでください ;)

## 学生がモデルをアップロードする方法
学生は、joblibライブラリを使用してシリアル化されたモデルをアップロードする必要があります。学生がモデルを作成する方法については、temp/create-model.pyファイルを確認してください：
```
from joblib import dump
dump(clf_lr, 'model.joblib')
```
モデルをアップロードするには、学生は「モデルを提出」フォームを使用するだけです：

<p align="center">
  <img alt="Upload Model" src="https://github.com/anfibil/classification-battle/raw/master/temp/screenshot2.png"> 
</p>
```