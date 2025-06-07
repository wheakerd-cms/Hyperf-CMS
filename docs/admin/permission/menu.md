#

> # 菜单类型 :id=menuTypeSelect

- **URL**: `/admin/permission/menu/menuTypeSelect`
- **方法**: `get`

#### 请求参数

```typescript
{
    id : Number;
    name: String;
}
```

#### 返回示例

/

> # 新增、编辑 :id=save

- **URL**: `/admin/permission/menu/save`
- **方法**: `post`

#### 请求参数

```typescript
{
    id: Number | undefined;
    type: Number;
    parentId: Number | undefined;
    title: String;
    name: String;
    icon: String;
    order: Number | undefined;
}
```

#### 返回示例

/

> # 【批量】删除 :id=delete

- **URL**: `/admin/permission/menu/delete`
- **方法**: `delete`

#### 请求参数

```typescript
{
    ids : Array<number>;
}
```

#### 返回示例

/

> # 【无分页】列表 :id=list

- **URL**: `/admin/permission/menu/list`
- **方法**: `get`

#### 请求参数

/