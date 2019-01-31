# cra [![Build Status Badge]][Build Status] [![Coverage Status Badge]][Coverage Status]
[Build Status]: https://travis-ci.org/sasamium/cra
[Build Status Badge]: https://travis-ci.org/sasamium/cra.svg?branch=master
[Coverage Status]: https://coveralls.io/github/sasamium/cra?branch=master
[Coverage Status Badge]: https://coveralls.io/repos/github/sasamium/cra/badge.svg?branch=master

## sub-commands

### `init`

設定ファイルを対話的に生成する。

```console
$ cra init
(ウィザードが起動する)

$ ls
.cra.yml
```

### `prepare:release-branch <major|minor|patch>`

最新バージョンをタグから算出し、指定されたバージョンのリリースブランチをチェックアウトする。

```console
$ git tag
1.0.0

$ git branch
* master

$ cra prepare:release-branch patch

$ git branch
  master
* release/1.0.1
```

### `release <VERSION>`

指定されたバージョンのリリースブランチを `master` ブランチへマージし、タグを打つ。

```console
$ git tag
1.0.0

$ git branch
  master
* release/1.1.0

$ cra release 1.1.0

$ git log -n 1 --oneline
(HEAD) xxxxx Merge branch 'release/1.1.0' into 'master'

$ git branch
* master

$ git tag
1.0.0
1.1.0
```

設定ファイル等で設定されている場合は、以下も同時に行う:

* Slack or Chatworkへの通知
* Github or Gitlabリリースの作成
