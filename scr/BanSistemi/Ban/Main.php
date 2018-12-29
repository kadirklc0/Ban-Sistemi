<?php

namespace BanSistemi\Ban;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{CommandSender, Command};
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\{Server, Player};
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;

class Main extends PluginBase implements Listener{

	public $O;
	public $b;
	public $c;
	public function onEnable(){
		$this->getLogger()->info("Ban Eklentisi Aktif");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder("Ban/"));
		$this->c = new Config($this->getDataFolder()."config.yml", Config::YAML);
		$this->b = new Config($this->getDataFolder()."banlananlar.yml", Config::YAML);
		$this->O = new Config($this->getDataFolder()."oyuncular.yml", Config::YAML);
	}
	
	public function oyunaGiris(PlayerLoginEvent $e){
		$o = $e->getPlayer();
   $oi = $o->getName();
   $cid = $o->getClientId();
    if($this->b->get($oi) == 0 && $this->b->get($oi) == null){
     return false;
     }
    if($this->b->get($o) == $cid){
     }else{
     $e->setCancelled(true);
     $e->setKickMessage("§cÜzgünüz..!\n§cBan Yemişsiniz, giremezsiniz..!");
     }
   }
   
	public function onCommand(CommandSender $s, Command $c, string $lbl, array $args):bool{
		$o = $s->getPlayer()->getName();
		if($c->getName() == "at"){
			if(isset($args[0])){
					 $oyuncu = $this->getServer()->getPlayer($args[0]);
					if($oyuncu instanceof Player){
								$sebep = $args[1];
								$oyuncu->kick("§8» §7".$o." §ctarafından\n§8» §e".$sebep." §csebebi ile oyundan atıldın!", false);
								$this->getServer()->broadcastMessage("§8» §7".$oyuncu->getName().", §e".$o." §ctarafından §e".$sebep." §csebebi ile oyundan atıldı.");
		}else{
			$s->sendMessage("§8» §cGirdiğin Nickteki Oyuncu Aktif Değil");
		}
		} else {
			$s->sendMessage("§8» §ckullanım: §7/at <Nick> <Sebep>");
		}
		}
		if($c->getName() == "puansil"){
			if(isset($args[0])){
				if(is_numeric($args[1])){
					 $oyuncu = $this->getServer()->getPlayer($args[0]);
					if($oyuncu instanceof Player){
								$puan = $args[1];
								$s->sendMessage("§8» §e".$oyuncu->getName()." §cadlı oyuncunun §e".$puan." §cpuanını sildiniz.");
								$this->O->set($oyuncu->getName(), $this->O->get($oyuncu->getName()) -$puan);
								$this->O->save();
		}else {
									$s->sendMessage("§8» §cGirdiğin Nickteki Oyuncu Aktif Değil!");
								}
		}else{
		$s->sendMessage("§8» §cLütfen sayısal değerler giriniz.");
							}
						}else{
				$s->sendMessage("§8» §ckullanım: §7/puansil <Nick> <Puan>");
			}
		}
		if($c->getName() == "puan"){
			if(isset($args[0])){
				if(is_numeric($args[2])){
					 $oyuncu = $this->getServer()->getPlayer($args[0]);
					if($oyuncu instanceof Player){
								$sebep = $args[1];
								$puan = $args[2];
								$oyuncu->sendMessage("§8------------------------\n§8» §e".$o." §cadlı yetkili tarafından §e".$sebep." §csebebiyle §e".$puan." §cpuan yedin.\n§8» §cunutma 10 puanın olunca banlanırsın!\n§8» §ctoplam puanın: §e".$this->O->get($oyuncu->getName())."\n§8------------------------");
								$s->sendMessage("§8------------------------\n§8» §c".$oyuncu->getName()." §eadlı oyuncuya §c".$sebep." §esebebi ile §c".$puan." §epuan attın.\n§8------------------------");
								$this->getServer()->broadcastMessage("§8------------------------\n§8» §7".$oyuncu->getName()." §eadlı oyuncu §c".$sebep." §esebebi ile §c".$puan." §epuan yedi.\n§8------------------------");
								$this->O->set($oyuncu->getName(), $this->O->get($oyuncu->getName()) +$puan);
					if($this->O->get($oyuncu->getName()) == 10){
						$cid = $oyuncu->getClientId();
									$oyuncu->kick("§8» §7$o §cadlı yetkili tarafından§e $sebep\n§8» §csebebi ile 10 puanı aştığın için\n §8» §cB A N L A N D I N!", false);
									$this->b->set($oyuncu->getName(), $cid);
									$this->b->save();
								}
								$this->O->save();
					
								} else {
									$s->sendMessage("§8» §cGirdiğin Nickteki Oyuncu Aktif Değil!");
								}
							}else{
								$s->sendMessage("§8» §cLütfen sayısal değerler giriniz.");
							}
						}else{
				$s->sendMessage("§8» §ckullanım: §7/puan <Nick> <Sebep> <Puan>");
			}
					}
					if($c->getName() == "puanim"){
						$s->sendMessage("§8------------------------------\n§8» §cyediğin puanlar §8» §e".$this->O->get($o)."§c puan\n§8» §cunutma 10 puanın olunca banlanırsın!\n§8------------------------------");
					}
					return true;
			}
			}
