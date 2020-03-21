<?php

declare(strict_types=1);

namespace redstonex\event;

use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use redstonex\block\Redstone;
use redstonex\block\RedstoneTorch;
use redstonex\RedstoneX;

/**
 * Class EventListener
 * @package redstonex\event
 */
class EventListener implements Listener {

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) {
        $block = $event->getBlock();

        if(!($block->getLevel()->getBlock($block->add(0, -1, 0)) instanceof Solid)) {
            $event->setCancelled();
        }

        switch ($event->getBlock()->getId()) {
            case Block::REDSTONE_TORCH:
                $event->setCancelled(true);
                $event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), new RedstoneTorch(0), false, true);
                if ($block instanceof RedstoneTorch) {
                    RedstoneX::consoleDebug("Placing block (Redstone Torch) (redstonex block)");
                    $block->activateRedstone();
                } else {
                    RedstoneX::consoleDebug("Placing block (Redstone Torch) (pmmp block)");
                    if ($event->getBlock()->getLevel()->getBlock($event->getBlock()->asVector3()) instanceof RedstoneTorch) {
                        RedstoneX::consoleDebug("Placed block (Redstone Torch) (pmmp block)");
                    }
                }
                return;
            case RedstoneX::REDSTONE_ITEM:
            case Block::REDSTONE_WIRE:
                if ($event->isCancelled()) {
                    $event->setCancelled(false);
                }
                $event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), new Redstone(RedstoneX::REDSTONE_WIRE, $event->getItem()->getDamage()), false, true);
                $event->setCancelled(true);
                if ($block instanceof Redstone) {
                    RedstoneX::consoleDebug("Placing block (Redstone Wire) (redstonex block)");
                    $block->activateRedstone();
                } else {
                    RedstoneX::consoleDebug("Placing block (Redstone Wire) (pmmp block)");
                }
                return;
        }
    }
}
