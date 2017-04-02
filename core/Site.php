<?php

namespace core;

/**
 * Class Site.
 * Contains methods for render such parts of site, 
 * as "header", "footer" and "content".
 */
class Site
{
    /**
     * The name of site.
     *
     * @var string $sitename the name of site.
     */
    public $sitename;

    /**
     * The name in 'Copyright' block.
     *
     * @var string $copyright the name in 'Copyright' block.
     */
    public $copyright;

    /**
     * Constructor of site.
     * Sets default values of site name and copyright.
     *
     * @constructor
     */
    public function __construct()
    {
        $this->sitename = "ATM Service";
        $this->copyright = $this->sitename;
        
        echo $this->IncludeMetaTags();
        echo $this->IncludeCss();
        echo $this->IncludeJSLib();
    }

    /**
     * Sets flash message.
     * Flash message is a window with text like Twitter Bootstrap flash.
     * You can set more than one flash message with the same type
     * by calling this function many times.
     *
     * @param string $type type of flash message: error, warning or success.
     * @param string $text text inside flash message.
     *
     * @return bool true if set successful;
     * false in other case.
     */
    public function SetFlash(string $type, string $text)
    {
        if (false === $this->ValidateFlashType($type)) {
            return false;
        }

        if (!isset($_SESSION[$type])) {
            $_SESSION["Flash"][$type] = [
                $text,
            ];
        } else {
            array_merge($_SESSION["Flash"][$type], [
                $text,
            ]);
        }

        return true;
    }

    /**
     * Get flash messages.
     *
     * @param string $type type of flash message.
     * If parameter is set -- then the function renders only
     * flashes with specific type.
     * Flashes priority:
     * 1. Error
     * 2. Warning
     * 3. Success
     *
     * @return bool true if get successful;
     * false in other case.
     */
    public function GetFlash(string $type = '')
    {
        if ('' !== $type) {
            if (false === $this->GetFlashesOfSpecificType($type)) return false;
        } else {
            // See flashes priority in comments to this function
            $this->GetFlashesOfSpecificType('error');
            $this->GetFlashesOfSpecificType('warning');
            $this->GetFlashesOfSpecificType('success');
        }
    }

    /**
     * Checks if flashes are set in session.
     *
     * @param string $type type of flash.
     * Set this parameter if you want to check 
     * if a flash with specific type is set.
     *
     * @return true if flash(or flashes) are set
     * or false in other case.
     */
    public function HasFlashes(string $type = '')
    {
        if ('' !== $type) {
            if (true === (isset($_SESSION['Flash']) && isset($_SESSION['Flash'][$type]))) return true;
        } else {
            if (true === (isset($_SESSION['Flash']) && isset($_SESSION['Flash']['error']))) return true;
            if (true === (isset($_SESSION['Flash']) && isset($_SESSION['Flash']['warning']))) return true;
            if (true === (isset($_SESSION['Flash']) && isset($_SESSION['Flash']['success']))) return true;
        }
    }

    /**
     * Renders header part of site.
     *
     * @return bool true in case of successful render,
     * false in other case.
     */
    public function RenderHeader()
    {
        $header = '<div id="header">';
        // The name of site
        $header .= "<div class='header-title'>{$this->sitename}</div>";

        // The menu on bottom of header
        $header .= "<div class='header-menu'>";
        if (isset($_SESSION["Identity"])){
            $user = $_SESSION["Identity"];
            $header .= $this->RenderHeaderMenuItem("Logout ({$user->name} {$user->patronymic})", 'logout.php');
        } else {
            $header .= $this->RenderHeaderMenuItem('Login', 'login.php');
            $header .= $this->RenderHeaderMenuItem('Register', 'register.php');
        }
        $header .= $this->RenderHeaderMenuItem('Service staff', 'staff.php');
        $header .= $this->RenderHeaderMenuItem('Banks', 'banks.php');

        $header .= "</div>";
        $header .= "</div>";

        echo $header;

        return false;
    }

    /**
     * Starts to render content page.
     *
     * @return bool true in case of successful render,
     * false in other case.
     */
    public function StartContent()
    {
        $contentStart = '<div id="content">';

        echo $contentStart;

        return true;
    }

    /**
     * Ends rendering of content.
     *
     * @return bool true in case of successful render,
     * false in other case.
     */
    public function EndContent()
    {
        $contentEnding = '</div>';
        echo $contentEnding;

        return false;
    }

    /**
     * Includes javascript to site.
     *
     * @param string $js included javascript file path.
     *
     * @return bool true if javascript was included;
     * false in other case.
     */
    public function IncludeJS(string $JSFilePath)
    {
        if (false === file_exists(BASE_PATH . $JSFilePath)) {
            return false;
        }

        $js = "<script src='{$JSFilePath}' type=\"text/javascript\"></script>";

        echo $js;

        return true;
    }

    /**
     * Starts div in content.
     *
     * @param string $content content of div.
     * @param array $attributes attributes of element.
     *
     * @return bool true in case of successful render,
     * false in other case.
     */
    public function StartDiv(string $content = '', array $attributes = [])
    {
        $div = '<div ';

        foreach ($attributes as $key => $value) {
            $div .= "{$key}='{$value}' ";
        }

        $div .= '>' . $content;
        echo $div;

        return true;
    }

    /**
     * Ends opened div.
     *
     * @return bool true if closing successful.
     */
    public function EndDiv()
    {
        echo '</div>';

        return true;
    }

    /**
     * Renders div in content.
     *
     * @param string $content content of div.
     * @param array $attributes attributes of element.
     *
     * @return bool true in case of successful render,
     * false in other case.
     */
    public function RenderDiv(string $content = '', array $attributes = [])
    {
        if (false === $this->StartDiv($content, $attributes)) {
            return false;
        }
        $this->EndDiv();

        return true;
    }

    /**
     * Renders table with table names and columns.
     *
     * @param array $columnNames column names.
     * @param array $columns columns, default is just 'table'.
     * @param array $attributes attributes of essential.
     *
     * @return true if table was rendered;
     * false in other case.
     */
    public function RenderTable(array $columnNames, array $columns, array $attributes = [])
    {
        $table = "<table ";
        foreach ($attributes as $key => $value) {
            $table .= "{$key}='{$value}' ";
        }
        $table .= ">";
        
        $columnNamesLength = count($columnNames);

        $table .= "<tr>";
        for ($i = 0; $i < $columnNamesLength; $i++) {
            $table .= "<th>";
            $table .= $columnNames[$i];
            $table .= "</th>";
        }
        $table .= "</tr>";

        for ($i = 0, $columnsLength = count($columns); $i < $columnsLength; $i++) {
            $column = $columns[$i];
            $columnLength = count($column);

            if ($columnLength !== $columnNamesLength) {
                return false;
            }
            
            $table .= "<tr>";

            foreach ($column as $columnKey => $columnValue) {
                $table .= "<td>";
                $table .= $columnValue;
                $table .= "</td>";
            }
            $table .= "</tr>";
        }

        $table .= '</table>';

        echo $table;

        return true;
    }

    /**
     * Renders input element.
     *
     * @param string $type type of input.
     * @param array $attributes attributes of essential.
     *
     * @return true if table was rendered;
     * false in other case.
     */
    public function RenderInput(string $type = 'text', array $attributes = [])
    {
        $input = "<input type='{$type}' ";
        foreach ($attributes as $key => $value) {
            $input .= "{$key}='{$value}' ";
        }
        $input .= ">";

        echo $input;

        return true;
    }

    /**
     * Renders label element.
     *
     * @param string $text text of label.
     * @param array $attributes attributes of essential.
     *
     * @return true if table was rendered;
     * false in other case.
     */
    public function RenderLabel(string $text, array $attributes = [])
    {
        $label = "<label ";
        foreach ($attributes as $key => $value) {
            $label .= "{$key}='{$value}' ";
        }
        $label .= ">{$text}</label>";

        echo $label;

        return true;
    }

    /**
     * Starts rendering of form.
     *
     * @param string $action action after submit form.
     * @param string $method method of request of form.
     * @param array $attributes attributes of essential.
     *
     * @return true if table was rendered;
     * false in other case.
     */
    public function StartForm(string $action, string $method = 'POST', array $attributes = [])
    {
        $form = "<form action='{$action}' method='{$method}'";
        foreach ($attributes as $key => $value) {
            $form .= "{$key}='{$value}' ";
        }

        $form .= ">";

        echo $form;

        return true;
    }

    /**
     * Ends rendering of form.
     *
     * @return true if table was rendered;
     * false in other case.
     */
    public function EndForm()
    {
        echo "</form>";

        return true;
    }

    /**
     * Renders footer part of site.
     * 
     * @return string footer part of site.
     */
    public function RenderFooter()
    {
        $footer = "<div id='footer'>Copyright © {$this->copyright}, ". date('Y') ."</div>";

        return $footer;
    }

    /**
     * Renders meta tags of site.
     *
     * @return string meta tags of site.
     */
    private function IncludeMetaTags()
    {
        $meta = "";
        $meta .= "<meta charset='utf-8'>";

        return $meta;
    }

    /**
     * Includes CSS files for site.
     *
     * @return string included CSS of site.
     */
    private function IncludeCss()
    {
        $css = '';
        $css = '<link href="css/styles.css" rel="stylesheet">';

        return $css;
    }

    /**
     * Include JQuery with plugin for form validation
     *
     * @return string 'script' tags for include JQuery with plugin
     */
    private function IncludeJSLib()
    {
        $js = '';
        $js .= '
            <script src="//code.jquery.com/jquery-3.2.0.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
';
        return $js;
    }

    /**
     * Renders header menu item.
     *
     * @param string $itemName Item name.
     * @param string $itemLink Item link.
     *
     * @return string header menu item.
     */
    private function RenderHeaderMenuItem(string $itemName, string $itemLink = '#')
    {
        return "<div class='header-menu-item'><a href=\"{$itemLink}\">{$itemName}</a></div>";
    }

    /**
     * Gets a flash with specific type.
     *
     * @param string $type type of flash.
     *
     * @return bool true if flashes of specific type is set,
     * false in other case.
     */
    private function GetFlashesOfSpecificType(string $type)
    {
        if (false === (isset($_SESSION['Flash'][$type]) && true === $this->ValidateFlashType($type))) {
            return false;
        }
        foreach ($_SESSION['Flash'][$type] as $flashText) {
            $this->RenderDiv($flashText, [
                'class' => "flash-{$type}",
            ]);
        }
        $this->RemoveFlashesOfSpecificType($type);
    }

    /**
     * Removes a flash with specific type.
     *
     * @param string $type type of flash.
     *
     * @return bool true if flashes of specific type have been removed successfully,
     * false in other case.
     */
    private function RemoveFlashesOfSpecificType(string $type) 
    {
        unset($_SESSION['Flash'][$type]);
    }

    /**
     * Validates type of flash message.
     *
     * @param string $type type of flash message.
     *
     * @return true if type of flash is supported,
     * false in other case.
     */
    private function ValidateFlashType(string $type)
    {
        $supportedTypes = [
            'error',
            'warning',
            'success',
        ];
        
        if (false === in_array($type, $supportedTypes)) {
            return false;
        }

        return true;
    }
}
