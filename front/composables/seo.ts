export const applySeo = (itemsPage: any): void => {
  const config = useRuntimeConfig();
  const facebookAppId: string = config.public.facebookAppId as string;
  useHead({
    title: () => `Astro-Otter - ${itemsPage.title}`,
    meta: [
      { name: 'title', content: () => `Astro-Otter - ${itemsPage.title}` },
      { name: 'description', content: () => itemsPage.description },
    ]
  });

  useSeoMeta({
    fbAppId: facebookAppId,
    ogUrl: itemsPage.fullUrl,
    ogType: 'website',
    ogTitle: `Astro-Otter - ${itemsPage.title}`,
    ogDescription: itemsPage.description,
    ogImage: itemsPage.image,
    ogImageAlt: itemsPage.imageAlt,
    ogLocale: 'eng',
    ogSiteName: 'Astro Otter',
    twitterCard: 'summary_large_image',
    twitterSite: '',
    twitterTitle: `Astro-Otter - ${itemsPage.title}`,
    twitterDescription: itemsPage.description,
    twitterImage: itemsPage.image,
    twitterImageAlt: itemsPage.imageAlt
  });
}
