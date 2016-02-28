<?php
namespace App\Listeners;
use Dingo\Api\Event\ResponseWasMorphed;

class PaginationTransformResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        $content = $event->content;
        if (isset($content['meta'])) {
            // we have pagination

            $meta = $content['meta']['pagination'];
            $newMeta = [];
            //arrdd($meta);
            $newMeta['page'] = $meta['current_page'] - 1;
            $newMeta['per_page'] = $meta['per_page'];
            $newMeta['total_pages'] = $meta['total_pages'];
            $newMeta['total_entries'] = $meta['total'];
            //$newMeta['sort_by'] = $meta['total'];
            //$newMeta['order'] = $meta['total'];
            unset($content['meta']);
            $newContent = [];
            $newContent[] = $newMeta;
            $newContent[] = $content;
            $event->content = $newContent;

        }
    }
}
