# Laravel Docker 開発環境

## 必要なもの
- Docker Desktop

## セットアップ手順

1. リポジトリをクローン
2. コンテナを起動
docker compose up -d
3. Laravelをインストール
docker compose exec app bash
composer install
exit
4. .envを設定（DB_HOSTをdbに変更）
5. マイグレーション実行
docker compose exec app php artisan migrate
6. ブラウザで確認
http://localhost

## 使用サービス
- Laravel: http://localhost
- phpMyAdmin: http://localhost:8080
- MySQL: localhost:3306