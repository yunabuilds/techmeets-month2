# Week8: 会員制ブログ & 匿名掲示板

Laravel Breezeを使った認証機能と、Webセキュリティ対策を実装したアプリです。

## 機能一覧

### 会員制ブログ
- ユーザー登録・ログイン(Laravel Breeze)
- ログインユーザーのみ投稿可能
- 自分の投稿のみ編集・削除可能
- セキュリティ対策(XSS・CSRF・SQLインジェクション対策)

### 匿名掲示板
- ログインなしで閲覧・投稿可能
- ログインしている場合は本名で投稿、していない場合は「名無しさん」として投稿
- 自分の投稿のみ削除可能(匿名投稿は削除不可)

## セットアップ手順

1. リポジトリをクローンする
```bash
   git clone https://github.com/yunabuilds/techmeets-month2.git
```

2. Dockerコンテナを起動する
```bash
   docker compose up -d
```

3. コンテナに入り、依存パッケージをインストールする
```bash
   docker compose exec app bash
   composer install
```

4. `.env`ファイルを設定する(`.env.example`をコピーして作成)
```bash
   cp .env.example .env
   php artisan key:generate
```

5. マイグレーションを実行する
```bash
   php artisan migrate
```

6. フロントエンドをビルドする(ホストPC側で実行)
```bash
   npm install
   npm run dev
```

7. ブラウザで以下のURLにアクセスする
```
   http://localhost
```
## セキュリティテスト

XSS・CSRF・SQLインジェクション対策のテスト結果は `SECURITY_TEST.md` を参照してください。