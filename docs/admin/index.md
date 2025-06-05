## 零碎功能

### 获取验证码 :id=get-captcha

- **URL**: `/admin/index/captcha`
- **方法**: `GET`

#### 请求参数
无

#### 返回示例

图片资源

### 登录 :id=login

- **URL**: `/admin/index/login`
- **方法**: `POST`

#### 请求参数

```typescript
{
    username: string;   //  账号
    password: string;   //  密码
    captcha: string;    //  验证码
}
```

#### 返回示例

图片资源