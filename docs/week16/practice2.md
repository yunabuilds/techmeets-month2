# 練習課題2：Trivy による脆弱性スキャンと修正

## スキャン結果（HIGH・CRITICAL のみ）

| 段階 | OS | vendor | 合計 | 結果ファイル |
|---|---|---|---|---|
| before（Debian・開発用） | 152 | ― | 152 | trivy-before.txt |
| after（Alpine・マルチステージ） | 0 | 1 | 1 | trivy-after.txt |
| after（Laravel 12 に更新後） | 0 | 0 | **0** | trivy-after-fixed.txt |

## 1. イメージの見直しで 152件 → 1件

before の脆弱性の多くは、本番では使わないソフトから出ていた。

- linux-libc-dev、libxml2-dev：ビルド用の部品
- openssh-client、perl：git と一緒に入ったもの
- curl：開発用 Dockerfile で入れたもの

after では Alpine ベース・マルチステージビルドにして、これらを入れなかったため OS 部分は 0件になった。

## 2. 残った1件の修正：Laravel 11 → 12

- 脆弱性：GHSA-5vg9-5847-vvmq（HIGH）
  Laravel Framework: CRLF injection in default email rule
- 原因：laravel/framework v11.55.0
- 修正版：12.60.0 / 13.10.0（Laravel 11 はサポート終了のため修正版なし）
- 対応：別ブランチ（upgrade-laravel-12）で Laravel 12.70.0 に更新

### アップデート前後のテスト結果

| | バージョン | テスト結果 |
|---|---|---|
| 更新前 | 11.55.0 | 57 passed（124 assertions） |
| 更新後 | 12.70.0 | 57 passed（124 assertions） |

テストがすべて通ったため、更新による不具合はないと判断した。
イメージサイズは 269MB → 272MB（要件の 300MB 以下を維持）。

## 3. composer audit で見つかった追加の1件

- symfony/yaml v7.4.14（CVE-2026-109413：細工された YAML で処理が停止する）
- 使っているのは laravel/sail（開発用）のみ。本番イメージには --no-dev で含まれないため、Trivy では検出されなかった
- 開発環境の安全のため v7.4.21 に更新し、composer audit で 0件を確認

## 学んだこと

- 使わないソフトを入れなければ、脆弱性も入らない
- フレームワークのサポートが終わると、修正版が出なくなる
- テストがあると、大きなバージョンアップも安全に確認できる