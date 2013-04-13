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

        <h3>{gt text='Documentation'}</h3>
        <h4>{gt text='Barcodes'}</h4>
        <ul>
            <li>
                <h5>{gt text='1D barcodes'}</h5>
                <ul>
                    <li>
                        <code>{literal}{barcode1d code="test"}{/literal}</code>
                        {barcode1d code="test"}
                    </li>
                    <li>
                        <code>{literal}{barcode1d code="123abc" color='green' width='3' height='50'}{/literal}</code>
                        {barcode1d code="123abc" color='green' width='3' height='50'}
                    </li>
                </ul>
            </li>
            <li>
                <h5>{gt text='2D barcodes'}</h5>
                <ul>
                    <li>
                        <code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf"}{/literal}</code>
                        {barcode2d code="https://www.github.com/cmfcmf/Tcpdf"}
                    </li>
                    <li>
                        <code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" color='orange' type='DATAMATRIX'}{/literal}</code>
                        {barcode2d code="https://www.github.com/cmfcmf/Tcpdf" color='orange' type='DATAMATRIX'}
                    </li>
                </ul>
            </li>
        </ul>

        <br />
        <br />


    </div>
</form>

{$footer}