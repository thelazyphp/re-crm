import Base from './Base'

export default class User extends Base {
    get schema () {
        return {
            id: undefined,
            first_name: undefined,
            last_name: undefined,
            email: undefined
        }
    }
}
