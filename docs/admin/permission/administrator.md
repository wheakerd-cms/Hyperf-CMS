#

> # 新增、编辑 :id=save

- **URL**: `/admin/permission/administrator/save`
- **方法**: `POST`

#### 请求参数

```typescript
{
    id : Number | undefined;
    username:String;
    status:Boolean;
    nickname: String;
    password: String;
}
```

#### 返回示例

/

> # 修改状态 :id=changeStatus

- **URL**: `/admin/permission/administrator/changeStatus`
- **方法**: `patch`

#### 请求参数

```typescript
{
    id : Number;
    status:Boolean;
}
```

#### 返回示例

/

> # 【批量】删除 :id=delete

- **URL**: `/admin/permission/administrator/delete`
- **方法**: `delete`

#### 请求参数

```typescript
{
    ids : Array<number>;
}
```

#### 返回示例

/

> # 【分页】列表 :id=table

- **URL**: `/admin/permission/administrator/table`
- **方法**: `get`

#### 请求参数

```typescript
{
    search : JSON.stringify({
        nickname: String | undefined,
    });
    currentPage: Number | undefined;
    perPage: Number | undefined;
}
```

#### 字段说明

| 字段名        | 类型      | 说明   |
|:-----------|:--------|:-----|
| nickname   | string  | 昵称   |
| username   | string  | 账号   |
| status     | boolean | 状态   |
| createTime | string  | 创建时间 |
| updateTime | string  | 更新时间 |