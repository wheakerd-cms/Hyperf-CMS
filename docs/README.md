## 简介

本文档存根便于前后端联合开发制定，后续开发者可自行选择是否复用。

## 接口约定

1. 遵循 [`Mozilla`](https://developer.mozilla.org) 规范，所有请求方法按实际用途选择。
2. 后端向请求方（此处不明确指代，具体可参考不同语言的请求特性）写入缓冲区的数据涵盖部分必要结构数据如下：

```typescript
//  response header
{
    status: Number;
    authorization: string | undefined;
}
//  response body
{
    message: string;
    data: any | undefined;
}
```