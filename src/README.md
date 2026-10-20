
## Week 13: 独自ドメイン + HTTPS 公開

公開URL: https://myapp-yuna.com

### コマンド確認

#### dig myapp-yuna.com +short
出力: 52.197.47.31
意味:
- ドメイン名がどのIPアドレスを指しているかをDNSに問い合わせて確認するコマンド。EC2のElastic IPが返ったので、Aレコードが正しく反映されている。

#### curl -I https://myapp-yuna.com
出力: 出力（抜粋）: HTTP/1.1 200 OK / Server: nginx/1.28.3 / X-Powered-By: PHP/8.2.33
意味:
- 200 :リクエストが正常に処理されページが返った
- Server: nginx/1.28.3 ：リクエストを受けて返答を返したサーバーソフトがNginx
- X-Powered-By: PHP/8.2.33：アプリがPHP で動いていることを示す

#### sudo certbot certificates
出力: Expiry Date: 2026-12-27 (VALID: 89 days)

意味: 
- 有効期限: 2026-12-27 で、残り89日。証明書は有効な状態
- 自動更新: `certbot.timer` が1日2回動作し、期限が近づくと自動で更新する。`sudo certbot renew --dry-run` で更新の予行演習が成功した


### Nginx設定（抜粋）

EC2上の `/etc/nginx/sites-available/myapp.conf` に設定した内容。
Dockerコンテナ（Laravel, ポート8000）へのリバースプロキシと、
HTTP→HTTPS、wwwあり→wwwなしのリダイレクトを行っている。

```nginx
# 1. HTTPS, no www (アプリ本体)
server {
    listen 443 ssl;
    server_name myapp-yuna.com;

    location / {
        proxy_pass         http://localhost:8000;
        proxy_http_version 1.1;
        proxy_set_header   Host $host;
        proxy_set_header   X-Real-IP $remote_addr;
        proxy_set_header   X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header   X-Forwarded-Proto $scheme;
    }

    ssl_certificate /etc/letsencrypt/live/myapp-yuna.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/myapp-yuna.com/privkey.pem;
}

# 2. HTTPS, www あり → wwwなしへリダイレクト
server {
    listen 443 ssl;
    server_name www.myapp-yuna.com;

    ssl_certificate /etc/letsencrypt/live/myapp-yuna.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/myapp-yuna.com/privkey.pem;

    return 301 https://myapp-yuna.com$request_uri;
}

# 3. HTTP → HTTPS + wwwなしへリダイレクト
server {
    listen 80;
    server_name myapp-yuna.com www.myapp-yuna.com;

    return 301 https://myapp-yuna.com$request_uri;
}
```

### 振り返り
- 設定を変更したら、`curl -I` や `dig` で実際の挙動の確認を行った。
- 見た目（ブラウザ）だけでなく、レスポンスヘッダーやステータスコードまで確認することで、設定ミスにも気づきやすくなる。
---
## Week 14：テスト・品質チェックの結果

### テスト結果
```
Tests:    57 passed (124 assertions)
```

### カバレッジ
```
Total: 89.6 %
```
課題の対象であるブログアプリの範囲で計測。Week 7・9の練習課題とWeek 10〜12の機能は除外。

### ESLint
```
npx eslint resources/js/
→ エラー0件
```

### npm audit
```
修正前: 12 vulnerabilities (2 moderate, 8 high, 2 critical)
修正後: 7 vulnerabilities (2 moderate, 5 high)
```
`npm audit fix` で critical 2件を含む5件を修正。残り7件は Tailwind CSS 3系が使っている部品のもので、修正には Tailwind 4 へのメジャーアップデートが必要なため、別途対応予定。

### composer audit
```
修正前: Found 22 security vulnerability advisories affecting 4 packages
修正後: Found 4 security vulnerability advisories affecting 1 package
```
guzzle・commonmark・flysystem を更新。残り4件は laravel/framework のもので、Laravel 11系には修正版がないため、Laravel 12 へのアップグレードで対応予定。

---

## Week 15: CI/CD（GitHub Actions）

### 概要
GitHub Actionsを使って、pushやPRのたびにコードチェックとテストを自動で実行し、
masterにマージされたらEC2へ自動でデプロイされる仕組みを構築した。

### 構築したWorkflow

| ファイル | 動くタイミング | 内容 |
|---|---|---|
| lint.yml | push / PR | PHP CS Fixerでコードの書き方をチェック |
| test.yml | push / PR | MySQLを起動し、PHPUnitのテスト57件を実行 |
| deploy.yml | masterへのpush | SSHでEC2に接続し、git pull → コンテナ起動 → マイグレーション |

### 流れ
1. 作業ブランチにpush → LintとTestsが自動実行
2. PRを作成 → チェックがすべて合格したことを確認
3. masterにマージ → EC2へ自動デプロイ

### 教材から変更した点と理由
- `main` → `master`：このリポジトリの基本ブランチがmasterのため
- `working-directory: src`：Laravel本体がsrcフォルダにあるため
- PHP 8.3 → 8.2：手元のDocker環境と合わせ、環境の差をなくすため
- テスト前に `npm run build` を追加：CIには `public/build` がなく、画面を表示するテストが失敗したため
- デプロイのコマンドを `docker compose exec -T app` 経由に変更：サーバーではPHPがDockerの中で動いているため
- `set -e` を追加：途中のコマンドが失敗したら、そこでデプロイを止めるため

### つまずいた点と解決
- CIでテストが6件失敗 → ログから `manifest.json` がないことを特定。ビルド結果は `.gitignore` で上げない設定のため、CIの中でビルドするよう修正
- GitHub ActionsからEC2にSSHできない → セキュリティグループでSSHを自分のIPだけに制限していたため。デプロイの間だけ開け、終了後すぐに戻した
- デプロイ後にドメインで表示できない → サーバー内から `curl` で200 OKを確認し、デプロイは成功していると切り分け。原因はドメインの情報認証の未対応だった

### 振り返り
- 一つひとつの項目を手作業で確認しなくても、必要なチェックとテストが自動で実行され、
  さらにPRをマージしただけでサーバーに反映されたときに、自動化の便利さを実感した。
- デプロイ後にドメインで表示できず、原因の特定に時間がかかった。原因はお名前.comの
  ドメイン情報認証が済んでいなかったことだった。使っている道具やサービスの状態を
  日頃から丁寧に確認しておくことで、作業中の原因不明のエラーを減らせると感じた。
---

## Week 17：パフォーマンス最適化

### 概要
投稿一覧ページ（`/posts`）を対象に、N+1の解消・インデックス追加・Redisキャッシュ導入を行った。
あわせて、アップロード画像をWebPに変換し、CloudFront経由で配信するようにした。

計測の前提：
- ローカルのDocker環境で計測（テストデータとして投稿を500件投入）
- Lighthouse：シークレットウィンドウ・Mobile・Performanceのみ・Debugbarオフ
- クエリ数：Laravel Debugbarの「Queries」タブ

### Lighthouse（投稿一覧 `/posts`）
| 指標 | before | after |
|---|---|---|
| Performance | 52 | 55 |
| FCP | 14.8s | 18.7s |
| LCP | 14.9s | 18.7s |
| TBT | 200ms | 0ms |
| CLS | 0 | 0 |
| Speed Index | 35.9s | 35.4s |
| TTFB | 14,185ms | 10,800ms |

### N+1の解消（投稿一覧 `/posts`）
一覧に著者名を表示したところ、投稿10件ぶん著者を1件ずつ取得するN+1が発生したため、
`PostRepository::getAll()` で `with('user')` を使ってeager loadingに変更した。

| 状態 | クエリ数 |
|---|---|
| 著者名表示後（N+1あり） | 14 |
| `with('user')` で解消 | 5 |
| キャッシュ導入後 | 2 |

### インデックス（`posts.created_at`）
一覧の `latest()`（`ORDER BY created_at DESC`）で、500件すべてを並べ替えていたため、
`created_at` にインデックスを追加した。

対象クエリ：`select * from posts order by created_at desc limit 10`

| 項目 | before | after |
|---|---|---|
| type | ALL | index |
| key | NULL | posts_created_at_index |
| rows | 500 | 10 |
| Extra | Using filesort | Backward index scan |

### キャッシュ（Redis）
- キャッシュドライバーをRedisに変更（`redis:7-alpine` をdocker-composeに追加、`predis/predis` を導入）
- `PostRepository::getAll()` で、ページごとに10分間キャッシュ（`Cache::tags('posts')`）
- 投稿の作成・更新・削除時に `Cache::tags('posts')->flush()` でキャッシュを破棄
- 新しい投稿を作成したあと、一覧の先頭にすぐ反映されることを確認

| 項目 | before | after |
|---|---|---|
| クエリ数 | 5 | 2（sessionsのみ） |
| レスポンスタイム（Debugbar） | 18.48s / 15.88s / 16.62s / 9.7s（平均 約15.2s） | 18.19s / 18.01s / 10.56s（平均 約15.6s） |

### 画像最適化（WebP + CloudFront）
- Dockerfileで、GDをJPEG・WebP対応で再ビルド
- `S3UploadController` で、アップロード画像をWebP（品質80）に変換してS3に保存
- CloudFront（OAC）経由で配信

配信確認URL：
https://d19uo3nrh8rzcp.cloudfront.net/images/05cf67a5-7135-42b0-be11-626a68232a59.webp

### 考察
- N+1・インデックス・キャッシュの効果は、クエリ数（14 → 5 → 2）とEXPLAIN（rows 500 → 10）で確認できた。
- 一方、ページ全体の時間は、SQLの合計（約0.2秒）に対してTTFBが10〜14秒あり、ローカルのDocker環境（Windows）でのファイル読み込みの遅さが大部分を占めていた。
- そのため、Lighthouseやレスポンスタイムは計測ごとのばらつきが大きく（例：TTFBは短縮したがLCPは悪化）、今回の改善の効果ははっきりとは表れなかった。

### スクリーンショット
`docs/week17/` に保存（before / after のLighthouse、Debugbarのクエリ、EXPLAIN、WebP配信）

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

