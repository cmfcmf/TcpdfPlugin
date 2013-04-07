{$header}
<div class="z-admin-content-pagetitle">
    {icon type='gears' size='small'}
    <h3>{gt text='TCPDF plugin settings'}</h3>
</div>

<form id="tcpdf-configuration" class="z-form" action="{modurl modname='Extensions' type='adminplugin' func='dispatch' _plugin='Tcpdf' _action='updateConfig'}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}"/>
        <fieldset>
            <legend>{gt text='General settings'}</legend>

            <div class="z-informationmsg">{gt text='Nothing to configure yet!'}</div>
        </fieldset>
        {*
                <div class="z-buttons z-formbuttons">
                    {button src=button_ok.png set=icons/extrasmall __alt='Save' __title='Save' __text='Save'}
                    <a href="{modurl modname='Extensions' type='admin' func='viewPlugins' systemplugins=1}" title="{gt text='Cancel'}">{img modname=core src=button_cancel.png set=icons/extrasmall __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
                </div>
        *}
    </div>
</form>

{$footer}