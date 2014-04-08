<?php

namespace Message\Mothership\ControlPanel\Controller\Module\Dashboard;

use Message\Cog\Controller\Controller;

class OrdersActivity extends Controller implements DashboardModuleInterface
{
	const CACHE_KEY = 'dashboard.module.orders-activity';
	const CACHE_TTL = 3600;

	/**
	 *
	 * @return
	 */
	public function index()
	{
		if (false === $data = $this->get('cache')->fetch(self::CACHE_KEY)) {
			$since = strtotime(date('Y-m-d')) - (60 * 60 * 24 * 6);

			$in = $this->get('db.query')->run("SELECT COUNT(order_id) as num FROM order_item_status WHERE created_at > {$since} AND status_code = 0");
			$out = $this->get('db.query')->run("SELECT COUNT(order_id) as num FROM order_item_status WHERE created_at > {$since} AND status_code = 1000");

			$data = [
				'orders_in'  => $in[0]->num,
				'orders_out' => $out[0]->num,
			];

			$this->get('cache')->store(self::CACHE_KEY, $data, self::CACHE_TTL);
		}

		return $this->render('::modules:dashboard:orders-activity', $data);
	}
}