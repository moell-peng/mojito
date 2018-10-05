import routers from '../router/routers';

export const routeByName = (name) => {
  let router;

  let each = (routers, name) => {
    for (let item of routers) {
      if (item.name === name) {
        router = item
      }

      if (router) {
        break
      }

      if (item.hasOwnProperty('children') && item.children.length > 0) {
        each(item.children, name)
      }
    }
    return router;
  }

  return each(routers, name)
}

export const routeFormatTag = route => {
  return {
    name: route.name,
    fullPath: route.fullPath,
    title: route.meta.title ? route.meta.title : '',
    cache: route.meta && route.meta.cache,
    closable: !route.meta.notClosable,
    provider: route.meta.provider
  }
}
