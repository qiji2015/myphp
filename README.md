# myphp
demo
# API说明
 请求方式：POST
 通用参数：key=xxx
 返回格式：json(json.state=0失败，1成功；json.msg=数据或者错误内容)
### 添加公路API
 接口地址：/myphp/?ac=api&do=road
 参数：op=add
 参数：road_name=公路名称
### 获取公路API
 接口地址：/myphp/?ac=api&do=road
### 添加景区API
 接口地址：/myphp/?ac=api&do=spot
 参数：op=add
 参数：name=景区名称
 参数：abbreviation=景区简称
 参数：place=景区所在地
 参数：level=景区级别（3A\4A\5A）
### 获取景区API
 接口地址：/myphp/?ac=api&do=spot
### 添加车型API
 接口地址：/myphp/?ac=api&do=car
 参数：op=add
 参数：car_brand_name=车品牌
 参数：car_type_name=车型
### 获取车型API
 接口地址：/myphp/?ac=api&do=car
### 获取车品牌API
 接口地址：/myphp/?ac=api&do=carbrand
### 添加属性API
 接口地址：/myphp/?ac=api&do=attr
 参数：op=add
 参数：type=属性类型[值：风光、月份、假期、人群、周期]
 参数：attr_name=对应的值
### 获取属性API
 接口地址：/myphp/?ac=api&do=attr
 参数：type=属性类型[值：风光、月份、假期、人群、周期]
### 编辑文章属性API
 接口地址：/myphp/?ac=api&do=editattr
 参数：id 文章ID
 参数：from 出发地名 可选
 参数：to 目的地名 多个用半角逗号(,)分隔 可选
 参数：spot 景点名 多个用半角逗号(,)分隔 可选
 参数：road 公路名 多个用半角逗号(,)分隔 可选
 参数：car 车型 多个用半角逗号(,)分隔 可选
 参数：whither_type 风光 多个用半角逗号(,)分隔 可选
 参数：person 人群 多个用半角逗号(,)分隔 可选
 参数：month 月份 可选
 参数：holiday 假期 可选
 参数：cycle 周期 可选 

