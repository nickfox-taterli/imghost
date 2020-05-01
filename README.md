![此处输入图片的描述][1]

**Demo:https://imghost.tech/**

## 最简单的图床

- [x] 前端单文件(welcome.blade.php)
- [x] 控制器单文件(FileController.php)
- [x] 服务器无储存(Google Drive 储存)
- [x] 配合CF食用更佳(只有/store方法需要回源,其他可以强制缓存!)
- [x] 支持直接POST到接口上传(接口地址:https://imghost.tech/store)
- [ ] 没有管理系统(因为没有数据库)
- [ ] 本地储存Token/数据库储存Token(反正只是一个简单数组,一个文件存的下)
- [ ] 只是随便玩玩,以后都不改的啦!

## 注意：

1. 示例代码使用Laravel 6.0
2. 示例代码使用Mongodb Altas(Free Tier)
3. 每秒最多上传1000张图片(API限制)
4. 每秒可以同时访问无数张图片(CDN限制)

  [1]: https://imghost.tech/i/1FJIJI8Ew0B37i-2OYKcJgvnQZKG4Iynh "图片示例"