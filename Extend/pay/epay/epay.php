<?php 

/**
 * epaySdk类
 * php >= 5.5.0
 * @method $this pid(int $value = null) 商户ID
 * @method $this outTradeNo(string $value = null) 设置订单号
 * @method $this notifyUrl(string $value = null) 设置异步回调地址
 * @method $this returnUrl(string $value = null) 设置同步回调地址
 * @method $this type(string $value = null) 支付类型
 * @method $this name(string $value = '# {$out_trade_no} 在线支付') 设置订单名称
 * @method $this money(float $value = null) 设置订单金额
 * @method $this sitename(string $value = '在线支付') 设置网站名称
 * @method $this signType(string $value = 'MD5') 设置加密类型
 * @method $this sign(string $value = null) 签名值
 */
class SDK
{
    /**
     * 支付网关
     * @var string
     */
    protected $url;
    /**
     * 商户秘钥
     * @var string
     */
    protected $key;
    /**
     * 参数列表
     * @var array
     */
    protected $param = [
        'sitename' => '在线支付',
        'sign_type' => 'MD5',
    ];

    /**
     * 提交订单
     * @access public
     * @return $this
     * @throws EpayException
     */
    public function submit()
    {
        // 验证参数
        $this->verifyParam();
        // 设置默认参数
        $this->setDefaultParam();
        // 设置Sign签名
        $this->sign($this->getSign());
        return $this;
    }

    /**
     * 清空参数
     * @access public
     * @return $this
     */
    public function clearParam()
    {
        // 清空参数
        $this->param = [];
        return $this;
    }

    /**
     * 获取HTML FORM表单
     * @access public
     * @return string
     */
    public function getHtmlForm()
    {
        $html = '<form id="epay-submit" name="epay-submit" action="' . $this->url() . '/submit.php" method="POST">';

        foreach ($this->param as $key => $val) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }

        $html .= /** @lang text */
            '<input type="submit" value="正在提交"></form>';
        $html .= /** @lang text */
            '<script>document . forms[\'epay-submit\'].submit();</script>';

        // 清空参数
        $this->clearParam();

        return $html;
    }

    /**
     * 签名验证
     * @access public
     * @param array $param
     * @return bool
     * @throws EpayException
     */
    public function signVerify($param = null)
    {
        if (is_null($param)) {
            $param = $_REQUEST;
        }

        // 赋值参数
        $this->param = $param;

        if (!isset($param['sign'])) {
            return false;
        }

        // 验证参数
        $this->verifyParam(['key']);

        return $param['sign'] === $this->getSign();
    }

    /**
     * 设置默认参数
     * @access public
     * @return void
     */
    protected function setDefaultParam()
    {
        // 默认订单名称
        if (empty($this->name())) {
            $this->name("# {$this->outTradeNo()} 在线支付");
        }
    }

    /**
     * 获取Sign
     * @access protected
     * @return string
     */
    protected function getSign()
    {
        $string = '';
        $param = $this->param;

        // 对待签名参数数组排序
        ksort($param);
        reset($param);

        foreach ($param as $k => $v) {
            if ('sign' !== $k && 'sign_type' !== $k && '' !== trim($v) && $v !== null) {
                $string .= $k . '=' . $v . '&';
            }
        }
        //去掉最后一个&字符
        $string = substr($string, 0, strlen($string) - 1);
        $string .= $this->key();

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }

        return md5($string);
    }

    /**
     * 验证参数
     * @access protected
     * @param array $item 验证项
     * @return void
     * @throws EpayException
     */
    protected function verifyParam($item = null)
    {
        if (is_null($item)) {
            $item = ['pid', 'key', 'url', 'out_trade_no', 'notify_url', 'return_url', 'money'];
        }
        foreach ($item as $argsName) {
            if (empty($this->$argsName())) {
                $this->showMessage('参数‘' . $argsName . '’不能为空');
            }
        }
    }

    /**
     * 显示消息
     * @access protected
     * @return void
     * @throws EpayException
     */
    protected function showMessage()
    {
        $content = '<div style="text-align: center;margin-top: 50px;color: #FF0000">';
        foreach (func_get_args() as $args) {
            $content .= '<h4>' . $args . '</h4>';
        }
        $content .= '<div>';
        throw new EpayException($content);
    }

    /**
     * 驼峰转下划线
     * @access protected
     * @param string $str
     * @return string
     */
    protected function snake($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    /**
     * 设置支付网关
     * @access public
     * @param string $value
     * @return $this|string
     */
    public function url($value = null)
    {
        if (is_null($value)) {
            return $this->url;
        }
        $this->url = $value;
        return $this;
    }

    /**
     * 设置商户秘钥
     * @access public
     * @param string $value
     * @return $this|string
     */
    public function key($value = null)
    {
        if (is_null($value)) {
            return $this->key;
        }
        $this->key = $value;
        return $this;
    }

    /**
     * 参数操作
     * @access public
     * @param string $name
     * @param array $arguments
     * @return mixed|null|$this
     */
    public function __call($name, $arguments)
    {
        // 驼峰转下划线
        $name = $this->snake($name);
        // 获取参数值
        if (empty($arguments)) {
            return isset($this->param[$name]) ? trim($this->param[$name]) : null;
        }
        // 参数赋值
        $this->param[$name] = $arguments[0];

        return $this;
    }
}