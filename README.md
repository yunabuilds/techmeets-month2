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

---

# Week7: ブログシステム

## 概要
LaravelのMVCパターンを使った、投稿のCRUD機能を持つブログシステムです。

## 機能一覧
- 投稿一覧表示（ページネーション付き）
- 投稿詳細表示
- 投稿作成（タイトル、内容、カテゴリー）
- 投稿編集
- 投稿削除
- バリデーション実装
- Bladeレイアウト継承

## テーブル定義（posts）
| カラム名 | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー（自動採番） |
| title | string(200) | タイトル |
| content | text | 内容 |
| category | string | カテゴリー |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

## スクリーンショット
（ここに画像を貼ります）
![alt text](image.png)
![alt text](image-1.png)
![alt text](image-2.png)
![alt text](image-3.png)

---

# Week7練習1: 商品管理システム

## 概要
商品のCRUD機能を持つ、商品管理システムです。

## 機能一覧
- 商品一覧表示
- 商品詳細表示
- 商品作成（商品名、価格、説明、在庫数、カテゴリー）
- 商品編集
- 商品削除
- バリデーション実装

## テーブル定義（products）
| カラム名 | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー（自動採番） |
| name | string | 商品名 |
| price | integer | 価格 |
| description | text | 説明 |
| stock | integer | 在庫数 |
| category | string | カテゴリー |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

## スクリーンショット
![alt text](<スクリーンショット 2026-07-31 015305.png>)
![alt text](<スクリーンショット 2026-07-31 020227.png>)
![alt text](<スクリーンショット 2026-07-31 020307.png>)
![alt text](<スクリーンショット 2026-07-31 020411.png>)
![alt text](<スクリーンショット 2026-07-31 020713.png>)

---

# Week7練習2: 予約システム

## 概要
イベント予約システムです。1つのイベントに対して、複数の予約が紐づく構成になっています。

## 機能一覧
- イベント一覧
- イベント詳細
- 予約作成（名前、メール、人数、日時）
- 予約一覧
- 予約のキャンセル

## テーブル定義（events）
| カラム名 | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー（自動採番） |
| name | string | イベント名 |
| description | text | 説明 |
| date | dateTime | 開催日時 |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

## テーブル定義（reservations）
| カラム名 | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー（自動採番） |
| event_id | bigint | どのイベントへの予約か（外部キー） |
| name | string | 予約者名 |
| email | string | メールアドレス |
| number_of_people | integer | 人数 |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

## スクリーンショット
![alt text](image-7.png)
![alt text](image-8.png)
![alt text](image-9.png)
![alt text](image-10.png)