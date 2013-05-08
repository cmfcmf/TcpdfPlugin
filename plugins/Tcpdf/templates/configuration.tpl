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

            <div class="z-formbuttons">
                <a class="z-action-icon z-icon-es-regenerate" href="{modurl modname='Extensions' type='adminplugin' func='dispatch' _plugin='Tcpdf' _action='clearCache'}" title="{gt text='Clear cache now'}">{gt text='Clear cache now'}</a>
            </div>
        </fieldset>
        {*
                <div class="z-buttons z-formbuttons">
                    {button src=button_ok.png set=icons/extrasmall __alt='Save' __title='Save' __text='Save'}
                    <a href="{modurl modname='Extensions' type='admin' func='viewPlugins' systemplugins=1}" title="{gt text='Cancel'}">{img modname=core src=button_cancel.png set=icons/extrasmall __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
                </div>*}


        <h3>{gt text='Documentation'}</h3>
        <h4>{gt text='Pdf generation'}</h4>
        <ul>
            <li><p>{gt text='You can easily add a link to provide a download of the current page.'}</p>
                <code>{literal}{pdfLink &#95;&#95;text='Download this page as pdf'}{/literal}</code><br />
                {pdfLink __text='Download this page as pdf'}
            </li>
            <li>
                <p>{gt text='A second way is to add <code>&theme=pdf</code> to a link.'}</p>
            </li>
        </ul>
        <h4>{gt text='Barcodes'}</h4>
        <table class="z-datatable z-center">
            <thead>
            <tr>
                <th>{gt text='Code'}</th>
                <th>{gt text='Output'}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="z-odd">
                <td colspan="2">{gt text='1D barcodes'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode1d code="test"}{/literal}</code></td>
                <td>{barcode1d code="test"}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode1d code="123abc" color='green' width='3' height='50'}{/literal}</code></td>
                <td>{barcode1d code="123abc" color='green' width='3' height='50'}</td>
            </tr>
            <tr class="z-odd">
                <td colspan="2">{gt text='2D barcodes'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf"}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf"}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" color='orange' type='DATAMATRIX'}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" color='orange' type='DATAMATRIX'}</td>
            </tr>
            <tr class="z-odd">
                <td colspan="2">{gt text='Different file formats'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='html'}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='html'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='png'}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='png'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='svg'}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='svg'}</td>
            </tr>
            <tr>
                <td><code>{literal}{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='svgcode'}{/literal}</code></td>
                <td>{barcode2d code="https://www.github.com/cmfcmf/Tcpdf" format='svgcode'}</td>
            </tr>
            </tbody>
        </table>
    </div>
</form>

{$footer}