<?php

namespace Drupal\temporalio_queue;

use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueInterface;
use GuzzleHttp\ClientInterface;
use Drupal\temporalio_common\Service\CommonSettings;

class TemporalQueueDecoratingFactory extends QueueFactory {
  public function __construct(
    private ClientInterface $http,
    private CommonSettings $common,
    private QueueFactory $inner
  ) {}

  // 🔧 Match parent signature (add $reliable = FALSE)
  public function get($name, $reliable = FALSE): QueueInterface {
    if ($reliable) {
      return new TemporalReliableQueue($name, $this->http, $this->common);
    }
    return new TemporalQueue($name, $this->http, $this->common);
  }
}
