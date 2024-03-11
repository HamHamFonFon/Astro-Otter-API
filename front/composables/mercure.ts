const MERCURE_HOST: string|undefined = process.env.NUXT_MERCURE_HOST;
const MERCURE_TOPIC: string|undefined = process.env.NUXT_MERCURE_TOPIC
export const mercureConfig = {
  url: MERCURE_HOST,
  topic: MERCURE_TOPIC
}
