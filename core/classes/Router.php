<?

/**
 * Class Router
 * Created by SenEnter
 */

class Router
{

    private $domain;
    private $path_theme_image;

    function __construct()
    {
        $this->domain   =   DOMAIN_WEB;

        //Nếu ký tự cuối cùng là / thì bỏ đi
        if (substr($this->domain, -1) == '/') {
            $this->domain   =   substr($this->domain, 0, -1);
        }

        $this->path_theme_image =   $this->domain . '/theme/images/';
    }

    /**
     * Router::listArticleCate()
     * 
     * @param mixed $row
     * @return
     */
    function listArticleCate($row, $param = [])
    {
        $url  =  $this->domain . '/article/cate-' . $row['cat_id'] . '-' . to_slug($row['cat_name']) . '.html';
        if (!empty($param)) $url    .=  '?' . http_build_query($param);

        return $url;
    }

    /**
     * Router::detailArticle()
     * Generate URL cua bai viet
     * @param mixed $row (art_title_url)
     * @return string URL
     */
    function detailArticle($row, $param = [])
    {
        $url    =   $this->domain . '/article/' . $row['art_id'] . '-' . to_slug($row['art_title']) . '.html';
        if (!empty($param)) $url    .=  '?' . http_build_query($param);

        return $url;
    }

    /**
     * Router::srcArticle()
     * 
     * @param mixed $image_name
     * @param string $size
     * @return
     */
    // function srcArticle($image_name, $size = '')
    // {

    //     if ($size != '')    $size   .=  '/';

    //     $src    =   DOMAIN_IMAGE . '/article/' . $size . $image_name;

    //     return $src;
    // }

    /**
     * Router::listArticleCate()
     * 
     * @param mixed $row
     * @return
     */
    function hotelDetail($slug)
    {
        $url  =  $this->domain . '/hotel/' . $slug . '.html';
        return $url;
    }
}
