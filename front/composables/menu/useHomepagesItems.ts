import backgroundConstellation from '@/assets/images/background/constellations.jpg'
import backgroundCatalogs from '@/assets/images/background/background_vlt.jpg';

export const useHomepagesItems = () => {
  return {
    homepagesItems: [
      {
        routeName: 'catalogs',
        path: 'catalogs',
        text: 'catalogs.title',
        icon: '',
        image: backgroundCatalogs,
        description: 'catalogs.description',
        component: 'background'
      },
      {
        routeName: 'constellations',
        path: 'constellations',
        text: 'constellations.title',
        icon: '',
        image: backgroundConstellation,
        description: 'constellations.description',
        component: 'default'
      },
    ]
  }
}
