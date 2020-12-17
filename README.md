# システム名

HANEDA Handbook

# 概要

羽田空港のお土産店で働く販売員向けの商品情報システム

# システムの売り

各店舗毎に在庫登録が出来るため、在庫の有無を加味した商品案内ができる

# 環境

MAMP/MySQL/PHP

# 使い方

[商品課]
テストアカウント:
　ユーザID: toyoda
　パスワード: michiru

機能
　新規商品登録/商品編集・削除/ユーザ管理

[店舗社員]
テストアカウント
　ユーザID: wagashi
　パスワード: 8125

機能
　自店舗の商品一覧表示/在庫登録

[販売員]
ログイン不要

機能
商品検索/催事情報閲覧

# データベース

データベース:hnd_handbook

上記のDBをphpMyAdminに作成し、localhost.sqlをインポートしてください。
