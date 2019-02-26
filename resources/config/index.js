export default {
  admin: {
    authorize: {
      clientId: 2,
      clientSecret: '0amTuj8HL4BlPtUs8StQCNiKnNiPHdMg3qDPgOXa'
    },
    loginRouteName: 'adminLogin',

    dashboardName: 'adminDashboard',

    dashboardFullPath: '/admin/dashboard',

    appName: {
      fullName: 'Mojito Admin',
      abbrName: 'Mojito'
    },

    locale: 'en'
  },

  guardNames: [
    {
      label: 'admin',
      value: 'admin'
    }
  ],

  apiUrl: '',

  //Unit is day
  tokenTTL: 1,

  //Unit is day
  refreshTokenTTL: 7,

  showAuthorGitHubUrl: true,
}