<?php
/**
*
* 	@version 	2.1.0 June 8, 2012
* 	@package 	VDM Class
*	@copyright	Vast Development Method <https://www.vdm.io>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
* VDM Class
**/

class VDM
{
	public $_key = false;
	public $_is = false;
	
	public function __construct($Vk5smi0wjnjb)
	{
		// get the session
		$session = JFactory::getSession();
		$V2uekt2wcgwk = $session->get($Vk5smi0wjnjb, null);
		$h4sgrGsqq = $this->get($Vk5smi0wjnjb,$V2uekt2wcgwk);
		if (isset($h4sgrGsqq['nuut']) && $h4sgrGsqq['nuut'] && $h4sgrGsqq['status'] == 'Active' && isset($h4sgrGsqq['eiegrendel']) && strlen($h4sgrGsqq['eiegrendel']) > 300)
		{
			$session->set($Vk5smi0wjnjb, $h4sgrGsqq['eiegrendel']);
		}
		if ($h4sgrGsqq['status'] == 'Active' && isset($h4sgrGsqq['md5hash']) && strlen($h4sgrGsqq['md5hash']) == 32 && isset($h4sgrGsqq['customfields']) && strlen($h4sgrGsqq['customfields']) > 4)
		{
			$this->_key = md5($h4sgrGsqq['customfields']);
		}
		if ($h4sgrGsqq['status'] == 'Active' && isset($h4sgrGsqq['md5hash']) && strlen($h4sgrGsqq['md5hash']) == 32 )
		{
			$this->_is = true;
		}
	}
	
	private function get($Vk5smi0wjnjb,$V2uekt2wcgwk)
	{
		$Viioj50xuqu2 = unserialize(base64_decode('YTozOntzOjY6Imthc2llciI7czoyNjoiaHR0cHM6Ly93d3cudmRtLmlvL2FjY2Vzcy8iO3M6NjoiZ2VoZWltIjtzOjMyOiJEblI0K3VicVBvbT13TmVrWGNvZVF6OzNAdFhxOTddcyI7czo2OiJvbnRob3UiO2k6MTt9'));
		$Visqfrd1caus = time() . md5(mt_rand(1000000000, 9999999999) . $Vk5smi0wjnjb);
		$Vo4tezfgcf3e = date("Ymd");
		$Vozblwvfym2f = $_SERVER['SERVER_NAME'];
		$Vozblwvfym2fdie = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
		$V343jp03dxco = dirname(__FILE__);
		$Vc2rayehw4f0 = unserialize(base64_decode('czozNjoibW9kdWxlcy9zZXJ2ZXJzL2xpY2Vuc2luZy92ZXJpZnkucGhwIjs='));
		$Vlpolphukogz = false;
		if ($V2uekt2wcgwk) {
			$V2uekt2wcgwk = str_replace("\n", '', $V2uekt2wcgwk);
			$Vm5cxjdc43g4 = substr($V2uekt2wcgwk, 0, strlen($V2uekt2wcgwk) - 32);
			$Vbgx0efeu2sy = substr($V2uekt2wcgwk, strlen($V2uekt2wcgwk) - 32);
			if ($Vbgx0efeu2sy == md5($Vm5cxjdc43g4 . $Viioj50xuqu2['geheim'])) {
				$Vm5cxjdc43g4 = strrev($Vm5cxjdc43g4);
				$Vbgx0efeu2sy = substr($Vm5cxjdc43g4, 0, 32);
				$Vm5cxjdc43g4 = substr($Vm5cxjdc43g4, 32);
				$Vm5cxjdc43g4 = base64_decode($Vm5cxjdc43g4);
				$Vm5cxjdc43g4finding = unserialize($Vm5cxjdc43g4);
				$V3qqz0p00fbq  = $Vm5cxjdc43g4finding['dan'];
				if ($Vbgx0efeu2sy == md5($V3qqz0p00fbq  . $Viioj50xuqu2['geheim'])) {
					$Vbfbwv2y4kre = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $Viioj50xuqu2['onthou'], date("Y")));
					if ($V3qqz0p00fbq  > $Vbfbwv2y4kre) {
						$Vlpolphukogz = true;
						$Vwasqoybpyed = $Vm5cxjdc43g4finding;
						$Vcixw3trerrt = explode(',', $Vwasqoybpyed['validdomain']);
						if (!in_array($_SERVER['SERVER_NAME'], $Vcixw3trerrt)) {
							$Vlpolphukogz = false;
							$Vm5cxjdc43g4finding['status'] = "sleg";
							$Vwasqoybpyed = array();
						}
						$Vkni3xyhkqzv = explode(',', $Vwasqoybpyed['validip']);
						if (!in_array($Vozblwvfym2fdie, $Vkni3xyhkqzv)) {
							$Vlpolphukogz = false;
							$Vm5cxjdc43g4finding['status'] = "sleg";
							$Vwasqoybpyed = array();
						}
						$Vckfvnepoaxj = explode(',', $Vwasqoybpyed['validdirectory']);
						if (!in_array($V343jp03dxco, $Vckfvnepoaxj)) {
							$Vlpolphukogz = false;
							$Vm5cxjdc43g4finding['status'] = "sleg";
							$Vwasqoybpyed = array();
						}
					}
				}
			}
		}
		if (!$Vlpolphukogz) {
			$V1u0c4dl3ehp = array(
				'licensekey' => $Vk5smi0wjnjb,
				'domain' => $Vozblwvfym2f,
				'ip' => $Vozblwvfym2fdie,
				'dir' => $V343jp03dxco,
			);
			if ($Visqfrd1caus) $V1u0c4dl3ehp['check_token'] = $Visqfrd1caus;
			$Vdsjeyjmpq2o = '';
			foreach ($V1u0c4dl3ehp AS $V2sgyscukmgi=>$V1u00zkzmb1d) {
				$Vdsjeyjmpq2o .= $V2sgyscukmgi.'='.urlencode($V1u00zkzmb1d).'&';
			}
			if (function_exists('curl_exec')) {
				$Vdathuqgjyf0 = curl_init();
				curl_setopt($Vdathuqgjyf0, CURLOPT_URL, $Viioj50xuqu2['kasier'] . $Vc2rayehw4f0);
				curl_setopt($Vdathuqgjyf0, CURLOPT_POST, 1);
				curl_setopt($Vdathuqgjyf0, CURLOPT_POSTFIELDS, $Vdsjeyjmpq2o);
				curl_setopt($Vdathuqgjyf0, CURLOPT_TIMEOUT, 30);
				curl_setopt($Vdathuqgjyf0, CURLOPT_RETURNTRANSFER, 1);
				$Vqojefyeohg5 = curl_exec($Vdathuqgjyf0);
				curl_close($Vdathuqgjyf0);
			} else {
				$Vrpmu4bvnmkp = fsockopen($Viioj50xuqu2['kasier'], 80, $Vc0t5kmpwkwk, $Va3g41fnofhu, 5);
				if ($Vrpmu4bvnmkp) {
					$Vznkm0a0me1y = "\r\n";
					$V2sgyscukmgiop = "POST ".$Viioj50xuqu2['kasier'] . $Vc2rayehw4f0 . " HTTP/1.0" . $Vznkm0a0me1y;
					$V2sgyscukmgiop .= "Host: ".$Viioj50xuqu2['kasier'] . $Vznkm0a0me1y;
					$V2sgyscukmgiop .= "Content-type: application/x-www-form-urlencoded" . $Vznkm0a0me1y;
					$V2sgyscukmgiop .= "Content-length: ".@strlen($Vdsjeyjmpq2o) . $Vznkm0a0me1y;
					$V2sgyscukmgiop .= "Connection: close" . $Vznkm0a0me1y . $Vznkm0a0me1y;
					$V2sgyscukmgiop .= $Vdsjeyjmpq2o;
					$Vqojefyeohg5 = '';
					@stream_set_timeout($Vrpmu4bvnmkp, 20);
					@fputs($Vrpmu4bvnmkp, $V2sgyscukmgiop);
					$V2czq24pjexf = @socket_get_status($Vrpmu4bvnmkp);
					while (!@feof($Vrpmu4bvnmkp)&&$V2czq24pjexf) {
						$Vqojefyeohg5 .= @fgets($Vrpmu4bvnmkp, 1024);
						$V2czq24pjexf = @socket_get_status($Vrpmu4bvnmkp);
					}
					@fclose ($Vqojefyeohg5);
				}
			}
			if (!$Vqojefyeohg5) {
				$Vbfbwv2y4kre = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $Viioj50xuqu2['onthou'], date("Y")));
				if (isset($V3qqz0p00fbq) && $V3qqz0p00fbq  > $Vbfbwv2y4kre) {
					$Vwasqoybpyed = $Vm5cxjdc43g4finding;
				} else {
					$Vwasqoybpyed = array();
					$Vwasqoybpyed['status'] = "sleg";
					$Vwasqoybpyed['description'] = "Remote Check Failed";
					return $Vwasqoybpyed;
				}
			} else {
				preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $Vqojefyeohg5, $V1ot20wob03f);
				$Vwasqoybpyed = array();
				foreach ($V1ot20wob03f[1] AS $V2sgyscukmgi=>$V1u00zkzmb1d) {
					$Vwasqoybpyed[$V1u00zkzmb1d] = $V1ot20wob03f[2][$V2sgyscukmgi];
				}
			}
			if (!is_array($Vwasqoybpyed)) {
				die("Invalid License Server Response");
			}
			if (isset($Vwasqoybpyed['md5hash']) && $Vwasqoybpyed['md5hash']) {
				if ($Vwasqoybpyed['md5hash'] != md5($Viioj50xuqu2['geheim'] . $Visqfrd1caus)) {
					$Vwasqoybpyed['status'] = "sleg";
					$Vwasqoybpyed['description'] = "MD5 Checksum Verification Failed";
					return $Vwasqoybpyed;
				}
			}
			if (isset($Vwasqoybpyed['status']) && $Vwasqoybpyed['status'] == "Active") {
				$Vwasqoybpyed['dan'] = $Vo4tezfgcf3e;
				$Vqojefyeohg5ing = serialize($Vwasqoybpyed);
				$Vqojefyeohg5ing = base64_encode($Vqojefyeohg5ing);
				$Vqojefyeohg5ing = md5($Vo4tezfgcf3e . $Viioj50xuqu2['geheim']) . $Vqojefyeohg5ing;
				$Vqojefyeohg5ing = strrev($Vqojefyeohg5ing);
				$Vqojefyeohg5ing = $Vqojefyeohg5ing . md5($Vqojefyeohg5ing . $Viioj50xuqu2['geheim']);
				$Vqojefyeohg5ing = wordwrap($Vqojefyeohg5ing, 80, "\n", true);
				$Vwasqoybpyed['eiegrendel'] = $Vqojefyeohg5ing;
			}
			$Vwasqoybpyed['nuut'] = true;
		}
		unset($V1u0c4dl3ehp,$Vqojefyeohg5,$V1ot20wob03f,$Viioj50xuqu2['kasier'],$Viioj50xuqu2['geheim'],$Vo4tezfgcf3e,$Vozblwvfym2fdie,$Viioj50xuqu2['onthou'],$Vbgx0efeu2sy);
		return $Vwasqoybpyed;
	}
}
