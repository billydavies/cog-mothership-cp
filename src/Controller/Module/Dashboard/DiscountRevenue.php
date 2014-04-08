<?php

namespace Message\Mothership\ControlPanel\Controller\Module\Dashboard;

use Message\Cog\Controller\Controller;

class DiscountRevenue extends Controller implements DashboardModuleInterface
{
	const CACHE_KEY = 'dashboard.module.discount-revenue';
	const CACHE_TTL = 3600;

	/**
	 *
	 * @return
	 */
	public function index()
	{
		if (false === $data = $this->get('cache')->fetch(self::CACHE_KEY)) {
			$since = strtotime(date('Y-m-d')) - (60 * 60 * 24 * 6);

			$totals = $this->get('db.query')->run("SELECT SUM(total_discount) as sum_total_discount, SUM(total_gross) as sum_total_gross FROM order_summary WHERE total_discount > 0 AND created_at > {$since}");

			$data = [
				'total_discount' => $totals[0]->sum_total_discount,
				'total_gross'    => $totals[0]->sum_total_gross,
			];

			$this->get('cache')->store(self::CACHE_KEY, $data, self::CACHE_TTL);
		}

		return $this->render('::modules:dashboard:discount-revenue', $data);
	}
}