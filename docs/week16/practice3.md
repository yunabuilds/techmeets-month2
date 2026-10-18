# 練習課題3：アプリケーションログの集中管理（Fluentd）

## 構成

nginx と app（Laravel）のログを、Docker の fluentd ログドライバで Fluentd に送り、1つのファイルにまとめて保存する。

| ファイル | 役割 |
|---|---|
| docker-compose.logging.yml | 検証用の構成（fluentd・app・nginx） |
| docker/fluentd/fluent.conf | Fluentd の設定（24224番で受信 → 画面とファイルに出力） |

ポイント
- Laravel は LOG_CHANNEL=stderr でログを画面出力に流し、Docker が Fluentd に送る
- fluentd-async: "true" で、Fluentd の準備前でも app が起動できるようにした
- ログの保存先は名前付きボリューム（fluentd-log）。日ごとにファイルを分ける（timekey 1d）
- 開発環境と混ざらないよう、compose に name: techmeets-logging を指定

## 確認結果

| 確認したこと | 結果 |
|---|---|
| nginx のログが集まる | 31件 |
| app のログが集まる | 22件（PHP-FPM・ヘルスチェックなど） |
| Laravel のエラーログ（production.ERROR）が集まる | 1件 |
| 1つのファイル（techmeets.20261018.log）にまとめて保存 | 約12KB |
| コンテナを全部削除して作り直してもログが残る | 1件のまま残った |

エラーの確認には、DB をわざとつながず（DB_HOST=db）ログインを送信した。
ブラウザには「500 SERVER ERROR」だけが表示され（APP_DEBUG=false）、原因（db が見つからない）はログからだけ分かることを確認した。

## 作業中に見つけた問題と対応

### 1. 本番イメージで Laravel が起動しなかった
- 症状：php artisan を実行すると Class "Laravel\Pail\PailServiceProvider" not found
- 原因：開発環境で作られた bootstrap/cache/*.php（開発用パッケージの一覧）を COPY していた。vendor は --no-dev なので開発用パッケージが存在しない
- 対応：src/.dockerignore に bootstrap/cache/*.php を追加
- 気づき：基本課題のヘルスチェックは PHP-FPM の確認だけで、Laravel 自体は起動していなかったため見逃していた

### 2. Fluentd がファイルに書き出せなかった
- 症状：failed to flush the buffer（Errno::ENOENT "No such file or directory - fstat"）
- 原因：保存先を Windows のフォルダにバインドマウントしていたため、Fluentd のファイル操作に対応できなかった
- 対応：保存先を Docker の名前付きボリュームに変更

### 3. ログに個人情報が入る
- エラーログの SQL に、ログイン画面で入力したメールアドレスがそのまま記録された
- ログファイルは .gitignore で Git に入れない。テストではダミーのアドレスを使う

## 今後の改善案
- スタックトレースが1行ずつ別のログになっているので、JSON 形式で出力して1件にまとめる
- ヘルスチェック（GET /status）のログが多いので、必要に応じて除外する
- 本番では、保存先を S3 や CloudWatch Logs などの外部サービスにする

## 学んだこと
- json-file はコンテナを消すとログも消えるが、Fluentd で集めればコンテナと関係なく残せる
- 「アプリケーションログ」を確かめるには、実際に Laravel にログを出させる必要がある
- ログには個人情報が入るので、扱いに注意が必要