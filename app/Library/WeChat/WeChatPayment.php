<?php
declare(strict_types=1);

namespace App\Library\WeChat;

use App\Exception\LibraryException;
use Exception;
use OpenSSLAsymmetricKey;
use RuntimeException;
use WeChatPay\Builder;
use WeChatPay\BuilderChainable;
use WeChatPay\Crypto\Rsa;

/**
 * 微信支付
 *
 * @WeChatPayment
 * @\App\Library\WeChat\WeChatPayment
 *
 * @property-read  string              $platformCertificateSerial
 * @property-read OpenSSLAsymmetricKey $merchantPrivateKeyInstance
 * @property-read OpenSSLAsymmetricKey $onePlatformPublicKeyInstance
 */
final readonly class WeChatPayment
{
	private string               $platformCertificateSerial;
	private OpenSSLAsymmetricKey $merchantPrivateKeyInstance;
	private OpenSSLAsymmetricKey $onePlatformPublicKeyInstance;
	private BuilderChainable     $builderChainable;

	/**
	 * @param string $merchantId                  商户号
	 * @param string $merchantCertificateSerial   「商户API证书」的「证书序列号」
	 * @param string $platformCertificateSerial   「微信支付平台证书」的「平台证书序列号」(可以从「微信支付平台证书」文件解析，也可以在 商户平台 -> 账户中心 -> API安全 查询到)
	 * @param string $merchantPrivateKeyFilePath  从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
	 * @param string $platformCertificateFilePath 从本地文件中加载「微信支付平台证书」，可由内置CLI工具下载到，用来验证微信支付应答的签名
	 */
	public function __construct(
		string $merchantId,
		string $merchantCertificateSerial,
		string $platformCertificateSerial,
		string $merchantPrivateKeyFilePath,
		string $platformCertificateFilePath,
	)
	{
		$this->platformCertificateSerial    = $platformCertificateSerial;
		$this->merchantPrivateKeyInstance   = Rsa::from($merchantPrivateKeyFilePath);
		$this->onePlatformPublicKeyInstance = Rsa::from($platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);

		//  构造一个 APIv3 客户端实例
		$this->builderChainable = Builder::factory(
			[
				'mchid'      => $merchantId,
				'serial'     => $merchantCertificateSerial,
				'privateKey' => $this->merchantPrivateKeyInstance,
				'certs'      => [
					$platformCertificateSerial => $this->onePlatformPublicKeyInstance,
				],
			],
		);
	}


	/**
	 * JSAPI/小程序下单
	 *
	 * @used-by
	 *
	 * @link         https://pay.weixin.qq.com/doc/v3/merchant/4012791897
	 *
	 * @param string       $appid          公众账号ID
	 * @param string       $mchid          商户号
	 * @param string       $description    商品描述
	 * @param string       $out_trade_no   商户订单号
	 * @param string       $notify_url     商户回调地址
	 * @param array{
	 *     total: integer,
	 *     currency?: string,
	 * }                   $amount         订单金额
	 * @param array{
	 *     openid: string,
	 * }                   $payer          支付者信息
	 * @param string|null  $time_expire    支付结束时间
	 * @param string|null  $attach         商户数据包
	 * @param string|null  $goods_tag      订单优惠标记
	 * @param boolean|null $support_fapiao 电子发票入口开放标识
	 * @param null|array{
	 *      cost_price?: integer,
	 *      invoice_id?: string,
	 *      goods_detail?: array{
	 *          merchant_goods_id: string,
	 *          merchant_goods_id?: string,
	 *          goods_name?: string,
	 *          quantity: integer,
	 *          unit_price: integer,
	 *      },
	 *  }                  $detail         优惠功能
	 * @param null|array{
	 *      payer_client_ip: string,
	 *      device_id?: string,
	 *      store_info?: array{
	 *          id: string,
	 *          name?: string,
	 *          area_code?: string,
	 *          address?: string,
	 *      },
	 *  }                  $scene_info     场景信息
	 * @param null|array{
	 *      profit_sharing?: boolean,
	 *  }                  $settle_info    结算信息
	 *
	 * @return array{
	 *     prepay_id: string,
	 * }
	 * @noinspection SpellCheckingInspection
	 */
	public function placeOrder(
		string  $appid,
		string  $mchid,
		string  $description,
		string  $out_trade_no,
		string  $notify_url,
		array   $amount,
		array   $payer,
		?string $time_expire = null,
		?string $attach = null,
		?string $goods_tag = null,
		?bool   $support_fapiao = null,
		?array  $detail = null,
		?array  $scene_info = null,
		?array  $settle_info = null,
	): array
	{
		$uri = '/v3/pay/transactions/jsapi';

		$json = array_filter(
			compact(
				'appid',
				'mchid',
				'description',
				'out_trade_no',
				'time_expire',
				'attach',
				'notify_url',
				'goods_tag',
				'support_fapiao',
				'amount',
				'payer',
				'detail',
				'scene_info',
				'settle_info',
			),
			fn($value) => $value !== null,
		);

		return $this->__('post', $uri, compact('json'));
	}

	/**
	 * 微信支付订单号查询订单
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791899
	 *
	 * @param string $transaction_id 微信支付订单号
	 * @param string $mchid          商户号
	 */
	public function queryOrder(string $transaction_id, string $mchid): array
	{
		$uri = "/v3/pay/transactions/id/$transaction_id";

		$query = compact('mchid');

		return $this->__('get', $uri, compact('query'));
	}

	/**
	 * 商户订单号查询订单
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791900
	 *
	 * @param string $out_trade_no 商户订单号
	 * @param string $mchid        商户号
	 */
	public function inquiryOrder(
		string $out_trade_no,
		string $mchid,
	): array
	{
		$uri = "/v3/pay/transactions/out-trade-no/$out_trade_no";

		$query = compact('mchid');

		return $this->__('get', $uri, compact('query'));
	}

	/**
	 * 关闭订单
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791901
	 *
	 * @param string $out_trade_no 微信支付订单号
	 * @param string $mchid        商户号
	 */
	public function closeOrder(string $out_trade_no, string $mchid): array
	{
		$uri = "/v3/pay/transactions/out-trade-no/$out_trade_no/close";

		$json = compact('mchid');

		return $this->__('post', $uri, compact('json'));
	}

	/**
	 * 退款申请
	 *
	 * @used-by
	 *
	 * @document transaction_id和out_trade_no必须二选一进行传参
	 *
	 * @link     https://pay.weixin.qq.com/doc/v3/merchant/4012791903
	 *
	 * @param string                       $out_refund_no  商户退款单号
	 * @param array{
	 *      refund: integer,
	 *      from?: array{
	 *          account: string,
	 *          amount: integer,
	 *      },
	 *      total:integer,
	 *      currency:string,
	 *  }                                  $amount         金额信息
	 * @param null|string                  $transaction_id 微信支付订单号
	 * @param null|string                  $out_trade_no   商户订单号
	 * @param null|string                  $reason         退款原因
	 * @param null|string                  $notify_url     退款结果回调url
	 * @param null|'AVAILABLE'|'UNSETTLED' $funds_account  退款资金来源
	 * @param null|array{
	 *      merchant_goods_id: string,
	 *      wechatpay_goods_id?: string,
	 *      goods_name?: string,
	 *      unit_price: integer,
	 *      refund_amount: integer,
	 *      refund_quantity: integer,
	 *  }                                  $goods_detail   退款商品
	 *
	 * @return array{}
	 */
	public function refundRequest(
		string  $out_refund_no,
		array   $amount,
		?string $transaction_id = null,
		?string $out_trade_no = null,
		?string $reason = null,
		?string $notify_url = null,
		?string $funds_account = null,
		?array  $goods_detail = null,
	): array
	{
		$uri = '/v3/refund/domestic/refunds';

		$json = array_filter(
			compact(
				'out_refund_no',
				'amount',
				'transaction_id',
				'out_trade_no',
				'reason',
				'notify_url',
				'funds_account',
				'goods_detail',
			),
			fn($value) => $value !== null,
		);

		return $this->__('post', $uri, compact('json'));
	}

	/**
	 * 查询单笔退款（通过商户退款单号）
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791904
	 *
	 * @param string $out_refund_no 商户退款单号
	 */
	public function applyFor(string $out_refund_no): array
	{
		$uri = "/v3/refund/domestic/refunds/$out_refund_no";

		$query = compact('out_refund_no');

		return $this->__('get', $uri, compact('query'));
	}

	/**
	 * 发起异常退款
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791905
	 *
	 * @param string      $refund_id     微信支付退款单号
	 * @param string      $out_refund_no 商户退款单号
	 * @param string      $type          异常退款处理方式
	 * @param null|string $bank_type     开户银行
	 * @param null|string $bank_account  收款银行卡号
	 * @param null|string $real_name     收款用户姓名
	 */
	public function abnormalRefund(
		string  $refund_id,
		string  $out_refund_no,
		string  $type,
		?string $bank_type = null,
		?string $bank_account = null,
		?string $real_name = null,
	): array
	{
		$uri = "/v3/refund/domestic/refunds/$refund_id/apply-abnormal-refund";

		$json = array_filter(
			compact(
				'refund_id',
				'out_refund_no',
				'type',
				'bank_type',
				'bank_account',
				'real_name',
			),
			fn($value) => $value !== null,
		);

		return $this->__('post', $uri, compact('json'));
	}

	/**
	 * 申请交易账单
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791907
	 *
	 * @param string                        $bill_date 账单日期
	 * @param null|'ALL'|'SUCCESS'|'REFUND' $bill_type 账单类型
	 * @param null|'GZIP'                   $tar_type  压缩类型
	 */
	public function transactionStatement(string $bill_date, ?string $bill_type = 'ALL', ?string $tar_type = null): array
	{
		/** @noinspection SpellCheckingInspection */
		$uri = "/v3/bill/tradebill";

		$query = compact(
			'bill_date',
			'bill_type',
			'tar_type',
		);

		return $this->__('get', $uri, compact('query'));
	}

	/**
	 * 申请资金账单
	 *
	 * @used-by
	 *
	 * @link https://pay.weixin.qq.com/doc/v3/merchant/4012791908
	 *
	 * @param string                          $bill_date    账单日期
	 * @param null|'BASIC'|'OPERATION'|'FEES' $account_type 资金账户类型
	 * @param null|'GZIP'                     $tar_type     压缩类型
	 */
	public function financialStatement(
		string  $bill_date,
		?string $account_type = 'BASIC',
		?string $tar_type = null,
	): array
	{
		/** @noinspection SpellCheckingInspection */
		$uri = "/v3/bill/fundflowbill";

		$query = compact(
			'bill_date',
			'account_type',
			'tar_type',
		);

		return $this->__('get', $uri, compact('query'));
	}

	/**
	 * 统一请求
	 *
	 * @param string $method
	 * @param string $uri
	 * @param array  $options
	 *
	 * @return array
	 */
	private function __(string $method, string $uri, array $options): array
	{
		try {
			$response = $this->builderChainable->chain($uri)->{$method}($options);
		}
		catch (Exception $e) {
			throw new LibraryException($e->getMessage());
		}

		$contents = $response->getBody()->getContents();

		if (!json_validate($contents)) {
			throw new LibraryException('json error syntax !');
		}

		/** @var array $json */
		$json = json_decode($contents, true);

		return $json;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get(string $name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		throw new RuntimeException("Property '$name' does not exist");
	}
}