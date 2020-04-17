<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        aaa
        <!-- Sidebar user panel
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
-->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">管理菜单</li>
            <?php foreach ($allMenu as $k => $v): ?>
            <li class="treeview ">
                <a href="">
                    <i class="fa fa-th"></i>
                    <span>{{$v['pri_name']}}</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <?php if(isset($v['children'])):?>
                    <?php foreach ($v['children'] as $k1 => $v1): ?>
                    <li  class="<?php echo (($v1['route_url']) == 'null' || ($v1['route_url']) == '' ) ? 'treeview': '' ?> ">
                        <?php if( $v1['route_url'] == 'null' || empty($v1['route_url']) ): ?>
                        <a href="">
                            <i class="fa <?php echo empty($v1['pri_icon']) ? 'fa-circle-o' : $v1['pri_icon'] ;?>"></i>
                            {$v1.pri_name}1
                            <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <?php else :?>
                        <a href="<?php echo  url($v1['route_url']); ?>" target="mainFrame">
                            <i class="fa <?php echo empty($v1['pri_icon']) ? 'fa-circle-o' : $v1['pri_icon'] ;?>"></i>
                            {{$v1['pri_name']}}
                            <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <?php endif;?>

                        <ul class="treeview-menu">
                            <?php if(isset($v1['children'])):?>
                            <?php foreach ($v1['children'] as $k2 => $v2): ?>
                            <li>
                                <a href="<?php echo url($v2['route_url']); ?>" target="mainFrame">
                                    <i class="fa <?php echo empty($v2['pri_icon']) ? 'fa-circle-o' : $v2['pri_icon'] ;?> "></i>
                                    {{$v2['pri_name']}}
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endforeach; ?>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
