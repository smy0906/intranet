## 실행법

### PHP Built-in server
 
1. php -S localhost:8000 -t {현재 폴더}
2. 브라우저에서 localhost:8000 접속


### HHVM

1. /etc/hhvm/server.ini 파일 수정
    ```
    hhvm.php7.all = 1
    
    hhvm.server.port = 9000
    hhvm.server.type = proxygen  ;fastcgi
    
    hhvm.virtual_host[default][rewrite_rules][common][pattern] = "(.*)"
    hhvm.virtual_host[default][rewrite_rules][common][to] = "index.php/$1"
    hhvm.virtual_host[default][rewrite_rules][common][qsa] = true
    ```
2. hhvm -m server -c /etc/hhvm/server.ini
3. 브라우저에서 localhost:9000 접속
