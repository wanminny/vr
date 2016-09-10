<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    // 生产VR图
    public function actionGenerate()
    {

        $toolPath = "sudo  /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools   makepano";
        $config = " -config=templates/vtour-multires.config ";
        $image = "/Users/wanmin/Desktop/logoo.jpg";
        $parameters = " -panotype=cylinder -hfov=360 ";
        $command = $toolPath.$config.$image.$parameters;
        $returnValue = '';
        if(file_exists($image))
        {
            echo 111;
            if(file_exists("/Users/wanmin/Desktop/vtour"))
            {
                var_dump(exec("sudo  rm -rf vtour<<<EOF

EOF"));
            }
            $output = [];
            var_dump(exec($command,$output,$returnValue));
        }
        if($returnValue === 0)
        {
            echo "ok!";
        }
        else{
            echo "vr failure!";
        }

    }

}
