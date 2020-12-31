import SignIn from '../../pages/SignIn'
import SignUp from '../../pages/SignUp'
import Favorites from '../../pages/Favorites'
import Posts from '../../pages/Posts'
import Groups from '../../pages/Groups'
import Profile from '../../pages/Profile'

export default [
  {
    path: '/',
    redirect: '/posts'
  },
  {
    path: '/sign-in',
		component: SignIn,

    meta: {
      layout: 'empty',
      title: 'Вход'
    }
  },
  {
    path: '/sign-up',
		component: SignUp,

    meta: {
      layout: 'empty',
      title: 'Регистрация'
    }
  },
  {
    path: '/favorites',
		component: Favorites,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Избранные'
    }
  },
  {
    path: '/posts',
		component: Posts,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Посты'
    }
  },
  {
    path: '/groups',
		component: Groups,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Группы'
    }
  },
  {
    path: '/profile',
		component: Profile,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Профиль'
    }
  },
  {
    path: '*',
    redirect: '/'
  }
]
