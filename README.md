# MODX Internationalization
![Internationalization version](https://img.shields.io/badge/version-1.0.1-blue.svg) ![MODX Extra by Oetzie.nl](https://img.shields.io/badge/checked%20by-oetzie-blue.svg) ![MODX version requirements](https://img.shields.io/badge/modx%20version%20requirement-2.4%2B-brightgreen.svg)

## System settings

| Setting                  | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| internationalization.contexts | A valid JSON to group contexts. For example `[["web", "web-en", "web-de", "web-fr"], ["site-1", "site-2"]]`. If empty all contexts will be used. |
| internationalization.params | A comma separated list of URL parameters to be sent along with the translation url. |
| internationalization.use_pdotools | If yes and pdoTool is installed all chunks will be parsed by pdoTools. |

## InternationalizationGetLanguages snippet

The `InternationalizationGetLanguages` snippet will display the languages of the current resource. The current resource will
be redirected to the connected language resource, if the current resource isn't connected to a language resource it will be redirected
to the language home.

| Parameter                  | Description                                                                  |
|----------------------------|------------------------------------------------------------------------------|
| id | The ID of the resource. Default is the current resource ID. |
| tpl | The template of a language item. This can be a chunk name or prefixed with `@FILE` or `@INLINE`. |
| tplWrapper | The template of the wrapper. This can be a chunk name or prefixed with `@FILE` or `@INLINE`. |
| tplCurrent | The template of the current language item. This can be a chunk name or prefixed with `@FILE` or `@INLINE`. |
| skipCurrent | If set to `true` it will skip the current language. This can be set to `true` or `false`. |
| skipEmptyTranslation | If set to `true` it will skip all not connected languages. This can be set to `true` or `false`. Default is `false`. |

## Plug-ins

The plugin will set some placeholders.

#### internationalization.language.alternate

This placeholder will contain the header alternate languages. For example:

```
<link rel="alternate" hreflang="en-GB" href="https://www.modx.nl/en/" />
<link rel="alternate" hreflang="de-DE" href="https://www.modx.nl/de/" />
<link rel="alternate" hreflang="be-FR" href="https://www.modx.nl/fr/" />
<link rel="alternate" hreflang="x-default" href="https://www.modx.nl/nl/" />
```

### internationalization.language.suggestion

This placeholder will contain the suggest language of the current visitor. This placeholder is only set when the system setting
`internationalization.ip_provider` is set to a provider like `ipstack` or `ipapi`. The visitor data is set by IP and contains 
the `ip`, `country`, `lat`, `lng` and the available languages.
