<?php

namespace Boyner\Compailer;

use mysqli;
use stdClass;

class PageAndWidgetGenerate
{
    public function __construct()
    {
    }

    private function get_cms_custom_page(mysqli $mysqli)
    {
        $query = "SELECT * FROM wp_cms_custom_pages
                  WHERE type = 8
                  ORDER BY id ASC";
        $result = $mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function get_cms_widgets(mysqli $mysqli, int $pageId = null)
    {
        $query = "SELECT wcw.* FROM wp_cms_custom_pages as wccp
                  JOIN wp_cms_widgets as wcw ON wcw.customPageId = wccp.id
                  WHERE wccp.type = 8 AND wcw.customPageId = $pageId
                  ORDER BY wccp.id ASC";
        $result = $mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function importPagesAndWidgets($dbMp, $dbProd)
    {
        $getPages = $this->get_cms_custom_page($dbMp);

        foreach ($getPages as $key => $page) {
            $page = (object) $page;
            $query = "INSERT INTO wp_cms_custom_pages (type,name,title,createdAt,createdBy,updatedAt,updatedBy,status,lang)
                      VALUES (?,?,?,?,?,?,?,?,?)";

            $stmt = $dbProd->prepare($query);
            $stmt->bind_param("issssssis", $page->type, $page->name, $page->title, $page->createdAt, $page->createdBy, $page->updatedAt, $page->updatedBy, $page->status, $page->lang);
            $stmt->execute();
            $pageInsertId = $stmt->insert_id;
            $stmt->close();

            $getWidgets = $this->get_cms_widgets($dbMp, $page->id);

            foreach ($getWidgets as $key => $widget) {
                $widget = (object) $widget;

                $query = "INSERT INTO wp_cms_widgets (customPageId, type, title, displayOnWeb, displayOnMobile, displayOnApp, startedAt, endedAt, data, createdAt, createdBy, updatedAt, updatedBy, sort)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $dbProd->prepare($query);
                $stmt->bind_param("iisiiisssssssi", $pageInsertId, $widget->type, $widget->title, $widget->displayOnWeb, $widget->displayOnMobile, $widget->displayOnApp, $widget->startedAt, $widget->endedAt, $widget->data, $widget->createdAt, $widget->createdBy, $widget->updatedAt, $widget->updatedBy, $widget->sort);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}
