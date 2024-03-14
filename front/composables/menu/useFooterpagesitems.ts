export const useFooterpagesitems = () => {
  const { t } = useI18n();
  return {
    footerPageitems: [
      {
        path: 'index',
        text: t('home.title')
      },
      {
        path: 'contact',
        text: t('contact.title')
      }
    ]
  }
}
