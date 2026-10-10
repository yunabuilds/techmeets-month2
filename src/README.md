
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

