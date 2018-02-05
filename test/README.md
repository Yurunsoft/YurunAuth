## 说明

测试代码支持web和cli两种方式使用，仅作为参考。

### web

访问test/index.php，地址为`http://域名/test/控制器/动作`

### cli

`php index.php 控制器/动作 -参数名1 参数值1 -参数名2 参数值2`

## 功能测试

### 权限

#### 添加

控制器动作：`Rule/add`

`php index.php Rule/add -parentID 0 -name 1`

#### 修改

控制器动作：`Rule/update`

`php index.php Rule/update -id 1 -parentID 1 -name 2`

#### 删除

`php index.php Rule/delete -id 1`

### 角色

#### 添加

控制器动作：`Role/add`

`php index.php Role/add -parentID 0 -name 1`

#### 修改

控制器动作：`Role/update`

`php index.php Role/update -id 1 -parentID 1 -name 2`

#### 删除

`php index.php Role/delete -id 1`

### 角色的权限管理

#### 查询权限

控制器动作：`RoleRule/select`

`php index.php RoleRule/select -roleID 1`

#### 赋予权限

控制器动作：`RoleRule/add`

`php index.php RoleRule/add -roleID 1 -ruleID 2`

#### 保存权限

控制器动作：`RoleRule/save`

`php index.php RoleRule/save -roleID 1 -ruleIDs 2,3,4`

#### 移除权限

控制器动作：`RoleRule/delete`

`php index.php RoleRule/delete -roleID 1 -ruleID 2`

#### 是否拥有权限

控制器动作：`RoleRule/has`

`php index.php RoleRule/has -roleID 1 -ruleID 2`

### 用户的角色管理

#### 查询角色

控制器动作：`UserRole/select`

`php index.php UserRole/select -userID 1`

#### 赋予角色

控制器动作：`UserRole/add`

`php index.php UserRole/add -userID 1 -roleID 2`

#### 保存角色

控制器动作：`UserRole/save`

`php index.php UserRole/save -userID 1 -roleIDs 2,3,4`

#### 移除角色

控制器动作：`UserRole/delete`

`php index.php UserRole/delete -userID 1 -roleID 2`

#### 是否拥有角色

控制器动作：`UserRole/has`

`php index.php UserRole/has -userID 1 -roleID 2`

### 用户的权限管理

#### 查询权限

控制器动作：`UserRule/select`

`php index.php UserRule/select -userID 1`

#### 赋予权限

控制器动作：`UserRule/add`

`php index.php UserRule/add -userID 1 -ruleID 2`

#### 保存权限

控制器动作：`UserRule/save`

`php index.php UserRule/save -userID 1 -ruleIDs 2,3,4`

#### 移除权限

控制器动作：`UserRule/delete`

`php index.php UserRule/delete -userID 1 -ruleID 2`

#### 是否拥有权限

控制器动作：`UserRule/has`

`php index.php UserRule/has -userID 1 -ruleID 2`