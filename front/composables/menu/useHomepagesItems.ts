import backgroundConstellation from '@/assets/images/background/constellations.jpg'
import backgroundCatalogs from '@/assets/images/background/background_vlt.jpg';

export const useHomepagesItems = () => {
  const { t } = useI18n();

  return {
    homepagesItems: [
      {
        routeName: 'catalogs',
        path: 'catalogs',
        text: t('catalogs.title'),
        icon: '',
        image: backgroundCatalogs,
        description: t('catalogs.description'),
        component: 'background'
      },
      {
        routeName: 'constellations',
        path: 'constellations',
        text: t('constellations.title'),
        icon: '',
        image: backgroundConstellation,
        description: t('constellations.description'),
        component: 'default'
      },
    ]
  }
}
